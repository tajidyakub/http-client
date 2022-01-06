<?php
namespace Tj\Http\Interfaces;

use CurlHandle;
use Tjd\Http\HttpConfig;
use Tjd\Http\HttpRequest;

Interface ClientInterface
{
    protected HttpRequest $request;

    protected string $host;

    protected CurlHandle $client;

    protected HttpConfig $config;

    public function __construct(
        string $base, HttpConfig $config);

    public static function get(
        string $base,
        HttpConfig $config
    ):self;

    public static function post(
        string $base,
        HttpConfig $config,
        string $contentType,
        array $payload
    ):self;

    public function exec(
        string $path, 
        array $urlParams, 
        bool $debug = false);

    public function initOptions():void;

    public function initHeaders():void;

    public function setOption(
        int $curl_opt, mixed $value
    ):void;
}