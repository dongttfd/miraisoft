<?php

namespace App\Exceptions;

use App\Exceptions\Account\AccountNotExistedException;
use App\Http\Responses\ResponseCode;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            $map = $this->mapExceptionResponse($e);
            extract($map);

            if ($code) {
                return $this->responseJsonError($message, $code, $status);
            }

            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Map exception to response code and message
     *
     * @param  Throwable  $e
     * @return array
     */
    private function mapExceptionResponse(Throwable $e)
    {
        $message = 'error';
        $code = null;
        $status = null;

        switch (get_class($e)) {
            case AccountNotExistedException::class:
                $message = $e->getMessage();
                $code = $e->code;
                $status = Response::HTTP_UNPROCESSABLE_ENTITY;
                break;

            default:
                break;
        }

        return compact('status', 'code', 'message');
    }

    /**
     * Handle json response error
     *
     * @param string $message
     * @param int $code
     * @param int $httpStatus
     * @return \Illuminate\Http\JsonResponse
     */
    private function responseJsonError(
        string $message,
        int $code = ResponseCode::ERROR_CODE,
        int $httpStatus = Response::HTTP_INTERNAL_SERVER_ERROR
    ) {
        return response()->json(
            [
                'code' => $code,
                'message' => $message,
            ],
            $httpStatus
        );
    }

    /**
     * Handle Api Exception
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Throwable $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    private function handleApiException($request, Throwable $e)
    {
        switch (get_class($e)) {
            case ModelNotFoundException::class:
            case FileNotFoundException::class:
                return $this->responseJsonNotFound();
            case InternalServerError::class:
                return $this->responseJsonInternalServerError($e);
            default:
                break;
        }

        return parent::render($request, $e);
    }

    /**
     * Response not found json.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function responseJsonNotFound()
    {
        return $this->responseJsonError(
            'Resource not found.',
            ResponseCode::NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Response json internal server error.
     *
     * @param  \App\Exceptions\InternalServerError $e
     * @return \Illuminate\Http\JsonResponse
     */
    private function responseJsonInternalServerError(InternalServerError $e)
    {
        return $this->responseJsonError(
            'Internal Server Error.',
            ResponseCode::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
