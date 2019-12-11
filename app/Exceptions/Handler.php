<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
    public function report(Exception $exception)
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
    public function render($request, Exception $exception)
    {
        if($request->expectsJson()){

            //Imprime la validacion del modelo
            if($exception instanceof ValidationException){
                //No es necesario, laravel ya lo genera para la api.
            }

            //valida id no encontrado en api
            else if($exception instanceof ModelNotFoundException){
                $model = strtolower(class_basename($exception->getModel()));
                return $this->errorResponse("No existe ninguna instancia de {$model} con el id especificado.", 404);
            }

            //valida la autenticacion en api
            else if($exception instanceof AuthenticationException){
                return $this->errorResponse('No autenticado.', 401);
                //$this->unauthenticated($request, $exception);
            }

            //Valida la autorizacion en api
            else if($exception instanceof AuthorizationException){
                return $this->errorResponse('No posee permisos para ejecutar esta accion', 403);
            }

            //Valida notfound 404, cuando no se encuentra un recurso
            else if($exception instanceof NotFoundHttpException){
                return $this->errorResponse('No se encontró la URL especificada', 404);
            }

            //Valida methodNotAllowed 405, cuando no se encuentra la accion de un recurso
            else if($exception instanceof MethodNotAllowedHttpException){
                return $this->errorResponse('El metodo especificado en la petición no es válido', 405);
            }

            //Valida SqlState error de integridad foreign key
            else if($exception instanceof QueryException){
                $codigo = $exception->errorInfo[1];
                if($codigo == 1451){
                    return $this->errorResponse('No se puede eliminar el recurso de forma permanente, esta relacionado con otro recurso', 409);
                }
            }

            //Valida Excepcion limite de peticiones por minuto
            else if($exception instanceof ThrottleRequestsException){
                return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
            }

            //Valida las demas excepciones
            else if($exception instanceof HttpException){
                return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
            }

            //Valida error de tipo 500, y controla si esta en modo debug
            if(config('app.debug')){
                return parent::render($request, $exception); //Modo depuracion
            }
            else{
                return $this->errorResponse('Falla inesperada, intente luego.', 500); //Modo produccion
            }
        }else{
            //Peticiones web

            //Valida error del token csrf
            if(!config('app.debug')){
                if($exception instanceof TokenMismatchException){
                    return redirect()->back()->withInput($request->input());
                }
            }

        }


        return parent::render($request, $exception);
    }

    // protected function unauthenticated($request, AuthenticationException $exception)
    // {
    //     return $this->errorResponse('No autenticado.', 401);
    // }

    private function isFrontend($request){
        //valida que solo sean peticiones html
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

}
