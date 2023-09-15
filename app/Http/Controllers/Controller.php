<?php

namespace App\Http\Controllers;

use App\Http\Responses\ResponseCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController implements ResponseCode
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Handle Api response success
     *
     * @param array| null $data
     * @return JsonResponse
     */
    protected function responseJsonSuccess(array $data)
    {
        return response()->json([
            'data' => $data,
            'code' => self::SUCCESS_CODE,
            'message' => 'success',
        ]);
    }
}
