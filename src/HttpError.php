<?php

namespace Tjd\Http;

use Error;

class HttpError
{
    public static function new($message, $curl_errno)
    {
        $message = "HttpClient Error: {$curl_errno} - {$message}";
        return Throw new Error($message, $curl_errno);
    }
}