<?php

namespace App\Models;

class FileModel
{
    /**
     * @const int
     */

    const AWS_ENV = 0;

    /**
     * @const int
     */
    const K5_ENV = 1;

    /**
     * @const int
     */
    const T2_ENV = 3;

    /**
     * const Array
     */
    const MAP_ENV_FOLDER = [
        self::AWS_ENV => 'AWS',
        self::K5_ENV => 'K5',
        self::T2_ENV => 'T2',
    ];

    /**
     * @const int
     */
    const APP_1 = 0;

    /**
     * @const int
     */
    const APP_2 = 1;

    /**
     * const Array
     */
    const MAP_APP_FOLDER = [
        self::APP_1 => 'app1',
        self::APP_2 => 'app2',
    ];
}
