<?php

namespace App\Helpers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\LogManager;

/**
 * Class LogHelper
 * @package App\Helpers
 */
class LogHelper {

    /**
     * Wrapper for the generic laravel log class
     *
     * @return Application|LogManager|mixed
     */
    public static function logger()
    {
        return app('log');
    }
}
