<?php

namespace Tjd\Http;

use Tjd\Http\Interfaces\RequestInterface;

class HttpRequest
{
    protected string $host = '';

    protected string $base = '';

    protected string $path = '/';

    protected array $headers = [];

    protected string $method = 'GET';

    protected array $payload = [];

    protected mixed $posted;

    protected string $urlParams = '';

    protected int $time = 0;

    
    public function __construct(
        string $base,
        string $method = 'GET')
    {
        $this->base = $base;
        $this->method = $method;
    }

    public function all()
    {
        return [
            'url' => $this->url(),
            'method' => $this->method,
            'host' => $this->host,
            'time' => $this->time,
            'base' => $this->base,
            'path' => $this->path,
            'headers' => $this->headers,
            'payload' => $this->payload,
            'posted' => isset($this->posted) ? $this->posted : null,
            'urlParams' => $this->urlParams,
        ];
    }

    public function url()
    {
        $base = rtrim($this->base, '/');
        $path = ltrim($this->path, '/');
        $url = $base."/".$path;

        if ($this->urlParams != '')
        {
            $url .= "?{$this->urlParams}";
        }

        return $url;
    }

    public static function init(
        string $base, string $method = 'GET'): self
    {
        $r = new self($base, $method);
        return $r;
    }

    public function set(
        string $key,
        mixed $value): self
    {
        if (strpos($key,"."))
        {
            $keys = explode(".", $key);
            $this->{$keys[0]}[$keys[1]] = $value;
        }
        else
        {
            $this->{$key} = $value;
        }

        

        return $this;
    }

    public function get(
        string $props,
        string|null $key = null): mixed
    {
        return $key ? $this->{$props}[$key] : $this->{$props};
    }
}