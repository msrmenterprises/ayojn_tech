<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;

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
        'p_password',
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
        if ($exception instanceof MaintenanceModeException) {
            return response()->view('errors.503');
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return response()->view('errors.500', [], 500);
        }

      
        return parent::render($request, $exception);
    }

     protected function unauthenticated($request, AuthenticationException $exception)
    {
        foreach ($exception->guards() as $guard) {
            if ($guard == 'controlpanel') {
                return redirect(route('controlpanel.login'));
            }
            if ($guard == 'user') {
                return redirect(route('login'));
            }
        }

        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest(route('login'));
    }
}
