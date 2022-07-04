<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use SimpleXMLElement;
use Carbon\Carbon;
use Google_Client;//Google register y login
use Exception;
use App\Repositories\ResponsesAndLogout;//logout y respuestas

use App\Models\User;
use App\Models\Client;
use App\Models\Company;
use App\Models\Station;
use App\Models\Role;

class AuthController extends Controller
{
    private $response, $clientGoogle;

    public function __construct(ResponsesAndLogout $res)
    {
        $this->response = $res;
        $this->clientGoogle = new Google_Client(['client_id' => '358591636304-5sehkr6cb2t13lutk9rb76vjocv9rj0v.apps.googleusercontent.com']);
    }
    // Metodo para inicar sesion
    public function login(Request $request)
    {
        //Entra por menbresia
        if ($c = Client::where('membership', $request->email)->first()) {
            //Existe el email de esta membresia
            $user = User::where('id',$c->user_id)->get();
            if ($user[0]->email) {
                $request->merge(['email' => $user[0]->email]);
                // return $this->response->successResponse('data',$request->all());
            } else {
                return $this->response->errorResponse('No existe correo electrónico registrado');
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
                    // roles permitidos a la api
                    if ($rol->name == 'admin_master' || $rol->name == 'usuario') {
                        // dd($user[0]);
                        $validator = Validator::make($request->only('email'), ['email' => 'email']);
                        return ($validator->fails()) ?
                            $this->response->errorResponse('Por favor, ingrese un nuevo correo electrónico.',$user[0]->id
                            ) : $this->getToken($request, $user[0], $rol->name);
                    }
                }
                return $this->response->errorResponse('Usuario no autorizado', null);
            default:
                return $c ?
                    $this->response->errorResponse('Por favor, ingrese un nuevo correo electrónico.', $c->id) :
                    $this->response->errorResponse('Intente ingresar con su membresía.', null);
        }
    }
    public function loginGoogle(Request $request)
    {
        $userGoogle = $this->clientGoogle->verifyIdToken($request->idToken);

        if ($userGoogle) {
            $user = User::where('external_id', $userGoogle['sub'])->first();

            if ($user) {
                $request->merge(['email' => $user->email, 'password' => $user->membership]);
                return $this->getToken($request, $user, 'usuario');
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
                    if (!(Client::where('membership', $membership)->exists()))
                        break;
                }

                $request->merge([
                    'external_id' => $userGoogle['sub'],
                    'email' => $userGoogle['email'], 'name' => $userGoogle['given_name'],
                    'first_surname' => $userGoogle['family_name'], 'password' => bcrypt($membership)
                ]);

                $user = User::create($request->all());
                $request->merge(['user_id' => $user->id, 'points' => Company::find(1)->points, 'image' => $membership]);
                Client::create($request->all());
                $user->roles()->attach('5');
                Storage::disk('public')->deleteDirectory($user->membership);
                $request->merge(['password' => $membership]);
                return $this->getToken($request, $user, 'usuario');
            }

            return $this->response->errorResponse('Ya existe un usuario con el correo electrónico');
        }

        return $this->response->errorResponse('Intente más tarde');
    }

    // Metodo para registrar a un usuario nuevo
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string',
            'first_surname' => 'required|string',
            'email'         => ['required', 'email', Rule::unique((new User())->getTable())],
            'password'      => 'required|string|min:6',
        ]);
        if ($validator->fails())
            return $this->response->errorResponse($validator->errors(), null);
        // ****CONSERVAR****// Membresia aleatoria no repetible
        while (true) {
            $membership = 'E' . substr(Carbon::now()->format('Y'), 2) . rand(100000, 999999);
            if (!(Client::where('membership', $membership)->exists()))
                break;
        }
        //Crear Usuario y encriptar password
        $request->merge(['password' => bcrypt($request->password)]);
        error_log('Crea user');
        $user = User::create($request->all());
        if ($user) {
            //Obtener id role ya que no es 5 siempre
            $role = Role::where('name','=','usuario')->first();
            $user->roles()->attach($role->id);//Asignar role 5 al usuario
            //Creacion del cliente con menbresia
            $request->merge(['membership' => $membership, 'user_id' => $user->id, 'points' => Company::find(1)->points, 'image' => $membership]);
            Client::create($request->all());
        }
        // Storage::disk('public')->Client($user->membership);
        $request->merge(['password' => $request->password]);//Pasar el password sin encriptar
        return $this->getToken($request, $user, 'usuario');
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
        // dd($rol);

        if ($rol == 'usuario') {
            //User relacion con cliente no esta vacio llenar
            if ($user->client == null) {
                $request->merge([
                    'user_id' => $user->id,
                    'points' => 0,
                    'image' => $user->membership,
                    'acive' => 0
                ]);
                $user->client = Client::create($request->except('ids'));
                $user->client->save();
            }
            $user->client->update($request->only('ids'));
        }
        return $this->successReponse('token', $token);
    }
    // Metodo para copiar el historial de Tickets a SalesQR
    /* private function ticketsToSalesQRs($tickets, $status, $id)
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
                        $dataSaleQr->Product_id = Product::where('name', 'LIKE', '%' . $ticket->producto . '%')->first()->id;
                        $dataSaleQr->liters = $ticket->litro;
                        $dataSaleQr->points = $ticket->punto;
                        $dataSaleQr->payment = $ticket->costo;
                    }
                    if ($status == 3 || $status == 4) {
                        $dataSaleQr->Product_id = null;
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
    } */
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
