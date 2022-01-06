<?php

namespace Tjd\Http\Interfaces;

Interface RequestInterface
{
    protected string $base;

    protected string $path;

    protected array $headers;

    protected string $method;

    protected array $payload;

    protected array $urlParams;

    protected int $time;

    
    public function __construct(string|null $base, string $method = 'GET');

    public static function init(string $base, string $method = 'GET'): self;

    public function set(
        string $key,
        mixed $value): self;

    public function get(
        string $props,
        string|null $key): string|array|int|false;
}