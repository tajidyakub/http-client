<?php

namespace Tjd\Http\Interfaces;

use CurlHandle;
use Tjd\Http\HttpRequest;

Interface ResponseInterface
{
    protected int $code;

    protected array $header;

    protected string $body;

    protected array $meta;

    public function __construct(HttpRequest $request);

    public static function init(
        HttpRequest $request,
        CurlHandle $handle,
        int $code,
        $responseString,
    ): self;

    public function set(
        string $key, 
        mixed $value):self;

    public function get(
        string $props,
        string|null $key): mixed;


}