<?php

namespace App\Exceptions\Account;

use App\Http\Responses\ResponseCode;
use Exception;

class AccountNotExistedException extends Exception
{

    /**
     * @var int
     */
    public $code = ResponseCode::ACCOUNT_NOT_EXIST;

    public function __construct()
    {
        parent::__construct(__('Account not existed'));
    }
}
