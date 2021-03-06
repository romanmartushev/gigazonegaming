<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        // only log errors to Rollbar if in production
        if (env('APP_ENV') == 'production') {
            Log::error($e);
        }
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        /**
         * if request coming in is of ModelNotFoundException
         * and either the path is calling the api or is
         * an ajax request return json error message
         */
        if ($e instanceof ModelNotFoundException && (substr($request->path(), 0, 3) === 'api' || $request->ajax())) {
            return Response::json(['error' => [$e->getMessage()]]);
        }

        /**
         * Handle exception if TokenMismatchException and is an ajax request
         */
        if ($e instanceof TokenMismatchException && $request->ajax()) {
            return Response::json(['error' => ['Token mismatch, please refresh the page and try again.']]);
        }

        return parent::render($request, $e);
    }
}
