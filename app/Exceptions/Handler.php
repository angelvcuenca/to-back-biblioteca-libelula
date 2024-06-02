<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
class Handler extends ExceptionHandler
{

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Renderiza una excepción en una respuesta HTTP.
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {

        if ($request->expectsJson()) {

            return $this->handleApiException($request, $e);
        }


        return parent::render($request, $e);
    }

    /**
     * todo: Llama a los metodos de las excepciones
     *
     * @param  Request  $request
     * @param  Throwable  $exception
     * @return JsonResponse
     */
    private function handleApiException($request, Throwable $exception): JsonResponse
    {
        $statusCode = $this->getStatusCode($exception);

        $response = [
            'success' => false,
            //'trace'=> $exception->getTrace(),
            'errors' => $this->getMessage($exception),
            'code' => $statusCode
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * todo: Envia el codigo de la excepcion
     *
     * @param  Throwable  $exception
     * @return int
     */
    private function getStatusCode(Throwable $exception): int
    {
        if ($exception instanceof ValidationException) {
            return 422;
        }

        if ($exception instanceof AuthenticationException) {
            return 401;
        }

        if ($exception instanceof NotFoundHttpException) {
            return 404;
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return 405;
        }

        return method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
    }

    /**
     * todo: Mensaje de la excepcion
     *
     * @param Throwable $exception
     * @return array
     */
    private function getMessage(Throwable $exception): array
    {
        if ($exception instanceof ValidationException) {
            return [
                'message' => $exception->errors(),
            ];
        }

        if ($exception instanceof AuthenticationException) {

            return [
                'message' => 'Unauthenticated',
            ];
        }

        if ($exception instanceof NotFoundHttpException) {

            return [
                'message' => 'Endpoint not found',
            ];
        }

        if ($exception instanceof MethodNotAllowedHttpException) {

            return [
                'message' => 'Method not allowed',
            ];
        }
        return [
            'message' => $exception->getMessage()
        ];
       // return $exception->getMessage();
    }

}
