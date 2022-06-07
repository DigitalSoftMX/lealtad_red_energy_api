<?php

namespace App\Http\Controllers\Api;

use App\Canje;
use App\Client;
use App\DataCar;
use App\Empresa;
use App\Exchange;
use App\Gasoline;
use App\History;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lealtad\Tarjeta;
use App\Lealtad\Ticket;
use App\Repositories\ResponsesAndLogout;
use App\SalesQr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Station;
use App\User;
use Carbon\Carbon;
use Exception;
use Google_Client;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $response, $clientGoogle;

    public function __construct(ResponsesAndLogout $response)
    {
        $this->response = $response;
        $this->clientGoogle = new Google_Client(['client_id' => '358591636304-5sehkr6cb2t13lutk9rb76vjocv9rj0v.apps.googleusercontent.com']);
    }
    // Metodo para inicar sesion
    public function login(Request $request)
    {
        if ($u = User::where('username', $request->email)->first()) {
            if ($u->email) {
                $user = User::where('email', $u->email)->get();
                $request->merge(['email' => $u->email]);
            } else {
                return $this->response
                    ->errorResponse(
                        'No existe un correo electrónico registrado. Ingrese un correo electrónico.',
                        $u->id
                    );
            }
        } else {
            $user = User::where('email', $request->email)->get();
        }
        switch ($user->count()) {
            case 0:
                return $this->response->errorResponse('Lo sentimos, la cuenta no esta registrada.', null);
            case 1:

                if ($user[0]->external_id)
                    return $this->response->errorResponse('Intente iniciar sesión con su cuenta de google');

                foreach ($user->first()->roles as $rol) {
                    if ($rol->id == 4 || $rol->id == 5) {
                        $validator = Validator::make($request->only('email'), ['email' => 'email']);
                        return ($validator->fails()) ?
                            $this->response->errorResponse(
                                'Por favor, ingrese un nuevo correo electrónico.',
                                $user[0]->id
                            ) : $this->getToken($request, $user[0], $rol->id);
                    }
                }
                return $this->response->errorResponse('Usuario no autorizado', null);
            default:
                return $u ?
                    $this->response->errorResponse('Por favor, ingrese un nuevo correo electrónico.', $u->id) :
                    $this->response->errorResponse('Intente ingresar con su membresía.', null);
        }
    }
    public function loginGoogle(Request $request)
    {
        $userGoogle = $this->clientGoogle->verifyIdToken($request->idToken);

        if ($userGoogle) {
            $user = User::where('external_id', $userGoogle['sub'])->first();

            if ($user) {
                $request->merge(['email' => $user->email, 'password' => $user->username]);
                return $this->getToken($request, $user, 5);
            }

            return $this->response->errorResponse('El usuario no ha sido registrado anteriormente');
        }
        return $this->response->errorResponse('Intente más tarde');
    }
    // Registro con Google
    public function registerGoogle(Request $request)
    {
        // Verificacion del usuario de google
        $userGoogle = $this->clientGoogle->verifyIdToken($request->idToken);

        if ($userGoogle) {

            $userExists = User::where('email', $userGoogle['email'])->first();
            if (!$userExists) {
                // Membresia aleatoria no repetible
                while (true) {
                    $membership = 'E' . substr(Carbon::now()->format('Y'), 2) . rand(100000, 999999);
                    if (!(User::where('username', $membership)->exists()))
                        break;
                }
            
                $request->merge([
                    'username' => $membership, 'external_id' => $userGoogle['sub'],
                    'email' => $userGoogle['email'], 'name' => $userGoogle['given_name'],
                    'first_surname' => $userGoogle['family_name'], 'password' => bcrypt($membership)
                ]);
            
                $user = User::create($request->all());
                $request->merge(['user_id' => $user->id, 'points' => Empresa::find(1)->points, 'image' => $membership]);
                Client::create($request->all());
                $user->roles()->attach('5');
                Storage::disk('public')->deleteDirectory($user->username);
                $request->merge(['password' => $membership]);
                return $this->getToken($request, $user, 5);
            }

            return $this->response->errorResponse('Ya existe un usuario con el correo electrónico');
        }

        return $this->response->errorResponse('Intente más tarde');
    }

    // Metodo para registrar a un usuario nuevo
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'first_surname' => 'required|string',
            'email' => ['required', 'email', Rule::unique((new User)->getTable())],
            'password' => 'required|string|min:6',
            'number_plate' => $request->number_plate ? [Rule::unique((new DataCar())->getTable())] : '',
        ]);
        if ($validator->fails())
            return $this->response->errorResponse($validator->errors(), null);
        // Membresia aleatoria no repetible
        while (true) {
            $membership = 'E' . substr(Carbon::now()->format('Y'), 2) . rand(100000, 999999);
            if (!(User::where('username', $membership)->exists()))
                break;
        }
        $password = $request->password;
        $request->merge(['username' => $membership, 'password' => bcrypt($request->password)]);
        $user = User::create($request->all());
        $request->merge(['user_id' => $user->id, 'points' => Empresa::find(1)->points, 'image' => $membership]);
        Client::create($request->all());
        $user->roles()->attach('5');
        if ($request->number_plate != "" || $request->type_car != "") {
            $request->merge(['client_id' => $user->client->id]);
            DataCar::create($request->only(['client_id', 'number_plate', 'type_car']));
        }
        Storage::disk('public')->deleteDirectory($user->username);
        $request->merge(['password' => $password]);
        return $this->getToken($request, $user, 5);
    }
    // Método para actualizar solo el correo eletrónico de un usuario
    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'email' => [
                'required', 'email', Rule::unique((new User)->getTable())
            ],
        ]);
        if ($validator->fails()) {
            return $this->response->errorResponse($validator->errors(), $request->id);
        }
        $user = User::find($request->id);
        $user->update($request->only('email'));
        return $this->successReponse('email', $request->email);
    }
    // Metodo para cerrar sesion
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return $this->successReponse('message', 'Cierre de sesión correcto');
        } catch (Exception $e) {
            return $this->response->errorResponse('Token inválido', null);
        }
    }
    // Metodo para iniciar sesion, delvuelve el token
    private function getToken($request, $user, $rol)
    {
        if (!$token = JWTAuth::attempt($request->only('email', 'password')))
            return $this->response->errorResponse('Datos incorrectos', null);
        $user->update(['remember_token' => $token]);
        if ($rol == 5) {
            if ($user->client == null) {
                $request->merge(['user_id' => $user->id, 'current_balance' => 0, 'shared_balance' => 0, 'points' => 0, 'image' => $user->username, 'visits' => 0, 'acive' => 0]);
                $user->client = Client::create($request->except('ids'));
                $user->client->save();
            }
            if ($user->client->ids == null) {
                if (count($dataPoints = Tarjeta::where('number_usuario', $user->username)->get()) > 0) {
                    $user->client->points += $dataPoints->sum('totals');
                    $user->client->visits += $dataPoints->sum('visits');
                    // $user->client->update(['points' => $dataPoints->sum('totals'), 'visits' => $dataPoints->sum('visits')]);
                    $user->client->save();
                    foreach ($dataPoints as $dataPoint) {
                        $dataPoint->delete();
                    }
                }
                $this->ticketsToSalesQRs(Ticket::where([['number_usuario', $user->username], ['descrip', 'LIKE', '%puntos sumados%']])->get(), 1, $user->client->id);
                $this->ticketsToSalesQRs(Ticket::where([['number_usuario', $user->username], ['descrip', 'LIKE', '%Puntos Dobles Sumados%']])->get(), 2, $user->client->id);
                $this->ticketsToSalesQRs(Ticket::where([['number_usuario', $user->username], ['descrip', 'LIKE', '%Información errónea%']])->get(), 3, $user->client->id);
                $this->ticketsToSalesQRs(Ticket::where([['number_usuario', $user->username], ['descrip', 'LIKE', '%pendiente%']])->get(), 4, $user->client->id);
                $this->ticketsToSalesQRs(Ticket::where('number_usuario', $user->username)->get(), 5, null);
                foreach (History::where('number_usuario', $user->username)->get() as $history) {
                    try {
                        $dataHistoryExchange = new Exchange();
                        $dataHistoryExchange->client_id = $user->client->id;
                        $dataHistoryExchange->exchange = $history->numero;
                        $dataHistoryExchange->station_id = $history->id_station;
                        $dataHistoryExchange->points = $history->points;
                        $dataHistoryExchange->value = $history->value;
                        $dataHistoryExchange->status = 14;
                        $dataHistoryExchange->admin_id = $history->id_admin;
                        $dataHistoryExchange->created_at = $history->created_at;
                        $dataHistoryExchange->updated_at = $history->updated_at;
                        $dataHistoryExchange->save();
                    } catch (Exception $e) {
                    }
                    $history->delete();
                }
                foreach (Canje::where('number_usuario', $user->username)->get() as $canje) {
                    try {
                        if (!(Exchange::where('exchange', $canje->conta)->exists())) {
                            $dataExchange = new Exchange();
                            $dataExchange->client_id = $user->client->id;
                            $dataExchange->exchange = $canje->conta;
                            $dataExchange->station_id = $canje->id_estacion;
                            $dataExchange->points = $canje->punto;
                            $dataExchange->value = $canje->value;
                            $dataExchange->status = $canje->estado + 10;
                            $dataExchange->created_at = $canje->created_at;
                            $dataExchange->updated_at = $canje->updated_at;
                            $dataExchange->save();
                        }
                    } catch (Exception $e) {
                    }
                    $canje->delete();
                }
            }
            $user->client->update($request->only('ids'));
        }
        return $this->successReponse('token', $token);
    }
    // Metodo para copiar el historial de Tickets a SalesQR
    private function ticketsToSalesQRs($tickets, $status, $id)
    {
        foreach ($tickets as $ticket) {
            if ($status != 5) {
                try {
                    $dataSaleQr = new SalesQr();
                    $dataSaleQr->sale = $ticket->number_ticket;
                    $dataSaleQr->station_id = $ticket->id_gas;
                    $dataSaleQr->client_id = $id;
                    $dataSaleQr->created_at = $ticket->created_at;
                    $dataSaleQr->updated_at = $ticket->updated_at;
                    if ($status == 1 || $status == 2) {
                        $dataSaleQr->gasoline_id = Gasoline::where('name', 'LIKE', '%' . $ticket->producto . '%')->first()->id;
                        $dataSaleQr->liters = $ticket->litro;
                        $dataSaleQr->points = $ticket->punto;
                        $dataSaleQr->payment = $ticket->costo;
                    }
                    if ($status == 3 || $status == 4) {
                        $dataSaleQr->gasoline_id = null;
                        $dataSaleQr->liters = 0;
                        $dataSaleQr->points = 0;
                        $dataSaleQr->payment = 0;
                    }
                    $dataSaleQr->save();
                } catch (Exception $e) {
                }
            }
            $ticket->delete();
        }
    }
    // Metodo para actualizar la ip de una estacion
    public function uploadIPStation($station_id, Request $request)
    {
        $station = Station::where('number_station', $station_id)->first();
        $station->update($request->only('ip'));
        return "Dirección IP actualizado correctamente";
    }
    // Funcion mensaje correcto
    private function successReponse($name, $data)
    {
        return response()->json([
            'ok' => true,
            $name => $data
        ]);
    }
    // Metodo mensaje de error
    private function errorResponse($message, $email)
    {
        return response()->json([
            'ok' => false,
            'message' => $message,
            'id' => $email
        ]);
    }
    // Precios de gasolina para wordpress, no se incluye en el proyecto Ticket
    public function price(Request $request)
    {
        if ($request->place != null && $request->type != null) {
            $prices = new SimpleXMLElement('https://publicacionexterna.azurewebsites.net/publicaciones/prices', NULL, TRUE);
            $precio = '--';
            foreach ($prices->place as $place) {
                if ($place['place_id'] == $request->place) {
                    foreach ($place->gas_price as $price) {
                        if ($price['type'] == $request->type) {
                            $precio = (float) $price;
                            return $precio;
                        }
                    }
                }
            }
            return $precio;
        } else {
            return 'Falta el lugar o el tipo de gasolina';
        }
    }
}
