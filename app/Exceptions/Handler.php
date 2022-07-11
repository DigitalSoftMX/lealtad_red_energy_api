<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        //error 419 entra pagina expirada
        // if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
        //     $message = 'La Pagina ha expirado.';
        //     $status  = 419;
        //     return response()->view('errors.errors',['message' => $message,'status'=>$status]);
        // }
        //Erro 504 no existe la ruta
        // if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
        //     $message = 'La pagina no fue encontrada o no existe.';
        //     $status  = 504;
        //     return response()->view('errors.errors',['message' => $message,'status'=>$status]);
        // }
        return parent::render($request, $exception);
    }

}
