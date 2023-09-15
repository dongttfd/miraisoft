<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class BaseService
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = Log::channel('api');
    }
}
