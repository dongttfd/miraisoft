<?php

namespace App\Http\Responses;

/**
 * =========================================
 * ResponseCode interface
 * Define code to write on body response
 * =========================================
 */
interface ResponseCode
{
    /************************************************/
    /* 2xxx Success code                            */
    /************************************************/
    const SUCCESS_CODE = 2000;

    /************************************************/
    /* 4xxx Permission code                         */
    /************************************************/
    const BAD_REQUEST = 4000;
    const UNAUTHENTICATED = 4001;
    const PERMISSION_DENIED = 4003;
    const NOT_FOUND = 4004;
    const METHOD_NOT_ALLOW = 4005;

    /************************************************/
    /* 5xxx Server code                             */
    /************************************************/
    const HTTP_INTERNAL_SERVER_ERROR = 5000;

    /************************************************/
    /* 1xxx Error code                              */
    /************************************************/
    const ERROR_CODE = 1000;
    const ACCOUNT_NOT_EXIST = 1001;
    const ACCOUNT_LOGIN_MUST_UNIQUE = 1002;
}
