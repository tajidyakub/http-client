<?php
namespace Tjd\Http;

class HttpConfig
{
    protected array $config;

    public function __construct()
    {
        $this->config = require __DIR__ . "/../config/client.php";
    }

    public function get(string $key): mixed
    {
        return isset($this->config[$key]) ? $this->config[$key] : false;
    }

    public function set(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }

    public function all():array
    {
        return $this->config;
    }
}