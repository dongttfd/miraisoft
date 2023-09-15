<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    /**
     * @param array|null|\Illuminate\Support\Collection $data
     * @param string|null $resourceClassName
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    final protected function responseJsonSuccess($data, string $resourceClassName = null)
    {
        // if need transform to resource
        if ($resourceClassName) {
            return $this->transform($data, $resourceClassName)->additional([
                'code' => self::SUCCESS_CODE,
                'message' => 'success',
            ]);
        }

        return parent::responseJsonSuccess($data);
    }

    /**
     * Transform data via Api Resource
     *
     * @param mixed $data
     * @param string $className
     * @return Illuminate\Http\Resources\Json\JsonResource
     *
     * @throws \Exception
     */
    private function transform($data, string $className)
    {
        if (!is_a($className, JsonResource::class, true)) {
            throw new Exception("Transform response fail: The `{$className}` is not instanceof " . JsonResource::class);
        }

        return new $className($data);
    }

    /**
     * Handle Api response error
     *
     * @param string $message
     * @param int $code
     * @param int $httpStatus
     * @return JsonResponse
     */
    protected function responseJsonError(
        string $message,
        int $code = self::ERROR_CODE,
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
}
