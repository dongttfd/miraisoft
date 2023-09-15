<?php

namespace App\Http\Controllers\Api;

use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends ApiController
{
    /**
     * @var App\Services\FileService
     */
    private $fileService;

    /**
     * @param FileService $fileService
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function show(Request $request)
    {
        return response()->json(
            $this->fileService->getFile($request->only([
                'file',
                'app_env',
                'contract_server',
            ]))
        );
    }
}
