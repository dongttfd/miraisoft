<?php

namespace App\Services;

use App\Models\FileModel;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class FileService extends BaseService
{
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    private $disk;

    /**
     * @const Array
     */
    const ALLOW_ENV = [
        FileModel::AWS_ENV,
        FileModel::K5_ENV,
        FileModel::T2_ENV,
    ];

    /**
     * @const Array
     */
    const ALLOW_APP = [
        FileModel::APP_1,
        FileModel::APP_2,
    ];

    public function __construct()
    {
        $this->disk = Storage::disk('imprints_html_file');
    }

    /**
     * Get file from storage
     *
     * return Array
     */
    public function getFile($params)
    {
        extract($params);

        if (!isset($file) || !isset($app_env) || !isset($contract_server)
            || !is_string($file) || !in_array($app_env, self::ALLOW_ENV)
            || !in_array($contract_server, self::ALLOW_APP)
        ) {
            return $this->buildFileNotFoundResponse($file);
        }

        $path = $this->buildFilePath($file, $app_env, $contract_server);

        return !$this->disk->exists($path)
            ? $this->buildFileNotFoundResponse($file)
            : $this->buildFileResponse($file, $path);
    }

    /**
     * Build file path
     *
     * @param string $filename
     * @param string $env
     * @param string $server
     * @return string
     */
    private function buildFilePath($filename, $env, $server)
    {
        return FileModel::MAP_ENV_FOLDER[$env]
        . '/' . FileModel::MAP_APP_FOLDER[$server]
            . '/' . $filename;
    }

    /**
     * Build file response
     *
     * @param string $filename
     * @param string $path
     * @return Array
     */
    private function buildFileResponse($filename, $path)
    {
        return [
            'success' => true,
            'filename' => $filename,
            'content' => $this->disk->get($path),
            'message' => 'Seal Info response successfully',
        ];
    }

    /**
     * Build file not found response
     *
     * @param string $filename
     * @return Array
     */
    private function buildFileNotFoundResponse($filename)
    {
        return [
            'success' => false,
            'filename' => $filename,
            'message' => 'Seal Info response false',
        ];
    }

    /**
     * Get intersect files of 2 folders
     *
     * @param \Illuminate\Contracts\Filesystem\Filesystem $disk
     * @param string $firstPath
     * @param string $secondPath
     *
     * @return Array
     */
    public function getIntersectFiles(Filesystem $disk, $firstPath, $secondPath)
    {
        return array_intersect(
            array_map(
                function ($filename) use ($firstPath) {
                    return str_replace($firstPath, '', $filename);
                },
                $disk->files($firstPath)
            ),

            array_map(
                function ($filename) use ($secondPath) {
                    return str_replace($secondPath, '', $filename);
                },
                $disk->files($secondPath),
            )
        );
    }
}
