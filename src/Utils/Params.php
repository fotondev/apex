<?php

namespace App\Utils;

abstract class Params
{
    const ERROR_CODE_DEFAULT = 400;
    const ERROR_CODE_FORBIDDEN = 403;
    const ERROR_CODE_NOT_FOUND = 404;
    const ERROR_REQUEST_TIMEOUT = 408;
    const ERROR_CODE_LOCKED = 423;
    const ERROR_CODE_FREE_LIMIT = 452;
    const ERROR_CODE_INTERNAL_SERVER_ERROR = 500;
}