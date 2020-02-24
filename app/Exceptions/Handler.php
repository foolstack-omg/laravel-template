<?php

namespace App\Exceptions;

use App\Traits\ApiTrait;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Routing\Router;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class Handler extends ExceptionHandler
{
    use ApiTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        CommonException::class,
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
        if (method_exists($exception, 'render') && $response = $exception->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($exception instanceof Responsable) {
            return $exception->toResponse($request);
        }


        if ($this->expectsJson($request)) {
            $message = $exception->getMessage();
            if($exception instanceof NotFoundHttpException) {
                $message = 'NotFoundHttpException';
            }
            if($exception instanceof MethodNotAllowedException) {
                $message = 'MethodNotAllowedException';
            }
            if($exception instanceof MethodNotAllowedHttpException) {
                $message = 'MethodNotAllowedHttpException';
            }
            if(
                $exception instanceof AuthenticationException
                || $exception instanceof UnauthorizedException
                || $exception instanceof TokenBlacklistedException
                || $exception instanceof  JWTException
            ) {
                $code = 401; // 授权失败
            }
            if($exception instanceof ValidationException) {
                $errors['errors'] = $exception->errors();
                $code = 403; // (禁止） 服务器拒绝请求。
            }
            if (config('app.debug')) {
                $errors['file'] = $exception->getFile();
                $errors['line'] = $exception->getLine();
                $errors['trace'] = $exception->getTrace();
            }

            $errors['info'] = $message;
            if(!isset($code)) {
                $code = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : $exception->getCode();
            }


            return Router::toResponse($request, $this->failed($message,  $code, $errors));
        }
        return parent::render($request, $exception);
    }
}
