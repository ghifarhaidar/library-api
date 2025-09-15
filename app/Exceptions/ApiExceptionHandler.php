<?php
namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    /**
     * Map of exception classes to their handler methods
     */
    public static array $handlers = [
        AuthenticationException::class => 'handleAuthenticationException',
        AccessDeniedHttpException::class => 'handleAuthenticationException',
        AuthorizationException::class => 'handleAuthorizationException',
        ValidationException::class => 'handleValidationException',
        ModelNotFoundException::class => 'handleNotFoundException',
        NotFoundHttpException::class => 'handleNotFoundException',
        MethodNotAllowedHttpException::class => 'handleMethodNotAllowedException',
        HttpException::class => 'handleHttpException',
        QueryException::class => 'handleQueryException',
    ];

    /**
     * Handle authentication exceptions
     */
    public function handleAuthenticationException(
        AuthenticationException|AccessDeniedHttpException $e,
        Request $request
    ): JsonResponse {
        $this->logException($e, 'Authentication failed');

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($e),
                'status' => 401,
                'message' => 'Authentication required. Please provide valid credentials.',
                'timestamp' => now()->toISOString(),
            ]
        ], 401);
    }

    /**
     * Handle authorization exceptions
     */
    public function handleAuthorizationException(
        AuthorizationException $e,
        Request $request
    ): JsonResponse {
        $this->logException($e, 'Authorization failed');

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($e),
                'status' => 403,
                'message' => 'You do not have permission to perform this action.',
                'timestamp' => now()->toISOString(),
            ]
        ], 403);
    }

    /**
     * Handle validation exceptions
     */
    public function handleValidationException(
        ValidationException $e,
        Request $request
    ): JsonResponse {
        $errors = [];

        foreach ($e->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'field' => $field,
                    'message' => $message,
                ];
            }
        }

        $this->logException($e, 'Validation failed', ['errors' => $errors]);

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($e),
                'status' => 422,
                'message' => 'The provided data is invalid.',
                'timestamp' => now()->toISOString(),
                'validation_errors' => $errors,
            ]
        ], 422);
    }

    /**
     * Handle not found exceptions
     */
    public function handleNotFoundException(
        ModelNotFoundException|NotFoundHttpException $e,
        Request $request
    ): JsonResponse {
        $this->logException($e, 'Resource not found');

        $message = $e instanceof ModelNotFoundException
            ? 'The requested resource was not found.'
            : "The requested endpoint '{$request->getRequestUri()}' was not found.";

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($e),
                'status' => 404,
                'message' => $message,
                'timestamp' => now()->toISOString(),
            ]
        ], 404);
    }

    /**
     * Handle method not allowed exceptions
     */
    public function handleMethodNotAllowedException(
        MethodNotAllowedHttpException $e,
        Request $request
    ): JsonResponse {
        $this->logException($e, 'Method not allowed');

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($e),
                'status' => 405,
                'message' => "The {$request->method()} method is not allowed for this endpoint.",
                'timestamp' => now()->toISOString(),
                'allowed_methods' => $e->getHeaders()['Allow'] ?? 'Unknown',
            ]
        ], 405);
    }

    /**
     * Handle general HTTP exceptions
     */
    public function handleHttpException(HttpException $e, Request $request): JsonResponse
    {
        $this->logException($e, 'HTTP exception occurred');

        return response()->json([
            'error' => [
                'type' => $this->getExceptionType($e),
                'status' => $e->getStatusCode(),
                'message' => $e->getMessage() ?: 'An HTTP error occurred.',
                'timestamp' => now()->toISOString(),
            ]
        ], $e->getStatusCode());
    }

    /**
     * Handle database query exceptions
     */
    public function handleQueryException(QueryException $e, Request $request): JsonResponse
    {
        $this->logException($e, 'Database query failed', ['sql' => $e->getSql()]);

        // Handle specific database constraint violations
        $errorCode = $e->errorInfo[1] ?? null;

        switch ($errorCode) {
            case 1451: // Foreign key constraint violation
                return response()->json([
                    'error' => [
                        'type' => $this->getExceptionType($e),
                        'status' => 409,
                        'message' => 'Cannot delete this resource because it is referenced by other records.',
                        'timestamp' => now()->toISOString(),
                    ]
                ], 409);

            case 1062: // Duplicate entry
                return response()->json([
                    'error' => [
                        'type' => $this->getExceptionType($e),
                        'status' => 409,
                        'message' => 'A record with this information already exists.',
                        'timestamp' => now()->toISOString(),
                    ]
                ], 409);

            default:
                return response()->json([
                    'error' => [
                        'type' => $this->getExceptionType($e),
                        'status' => 500,
                        'message' => 'A database error occurred. Please try again later.',
                        'timestamp' => now()->toISOString(),
                    ]
                ], 500);
        }
    }

    /**
     * Extract a clean exception type name
     */
    private function getExceptionType(Throwable $e): string
    {
        $className = basename(str_replace('\\', '/', get_class($e)));
        return $className;
    }

    /**
     * Log exception with context
     */
    private function logException(Throwable $e, string $message, array $context = []): void
    {
        $logContext = array_merge([
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            // 'url' => request()->fullUrl(),
            // 'method' => request()->method(),
            // 'ip' => request()->ip(),
        ], $context);

        Log::warning($message, $logContext);
    }
}
