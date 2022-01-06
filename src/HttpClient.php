<?php

namespace Tjd\Http;

use CurlHandle;

class HttpClient
{
    protected bool $debug = false;

    protected HttpRequest $request;

    protected HttpResponse $response;

    protected HttpConfig $config;

    protected CurlHandle $client;

    /**
     * HttpClient Object construct.
     * 
     * @param string $base Base Url of the request.
     * @param HttpConfig $config Configuration object.
     * @param string $host Host string for sending the request.
     */
    public function __construct(
        string $base, 
        HttpConfig $config,
        string $host)
    {
        $this->client = curl_init();
        $this->request = new HttpRequest($base);
        $this->config = $config;
        $this->initOptions();
        $this->initHeaders();
        $this->request->set('host', $host);
        $this->request->set('headers.Host', $host);
        $this->response = new HttpResponse($this->request);
    }

    /**
     * Init HttpClient in static method.
     * 
     * @param string $base Base Url of the request.
     * @param HttpConfig $config Configuration object.
     * @param string $host Host for sending the request.
     * 
     * @return self HttpClient Object.
     */
    public static function init(
        string $base, 
        HttpConfig $config,
        string $host
    ):self
    {
        return new self($base, $config, $host);
    }

    /**
     * Prepare to execute a GET request.
     * 
     * @param string $accept Accept on the request header.
     * 
     * @return HttpClient $this HttpClient object.
     */
    public function get(
        string $accept = '*/*'
    ):self
    {
        $this->request->set('method', 'GET');
        
        if ($accept != '*/*')
        {
            $this->request->set('headers.Accept', $accept);
        }

        $this->setOption(CURLOPT_HTTPGET, true);
        
        return $this;
    }

    public function post(
        array $payload,
        string $contentType = 'json',
        string $accept = '*/*'
    ):self
    {
        $this->request->set('method', 'POST');
        $this->request->set('headers.Content-Type', $contentType);
        $this->request->set('payload', $payload);
        if ($accept != '*/*')
        {
            $this->request->set('headers.Accept', $accept);
        }

        if ($contentType =='form')
        {
            $this->request->set('headers.Content-Type', 'application/x-www-form-urlencoded');
            $this->request->set('posted', http_build_query($payload));
        }
        elseif($contentType=='json')
        {
            $this->request->set('headers.Content-Type', 'application/json');
            $this->request->set('posted', json_encode($payload));
        }
        elseif($contentType=='xml')
        {
            $this->request->set('headers.Content-Type', 'application/xml');
            $this->request->set('posted', $payload);
        }

        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $this->request->get('posted'));

        return $this;
    }

    public function debug(): self
    {
        $this->debug = true;
        $this->response->debug();
        return $this;
    }

    public function exec(
        string $path = '/', 
        array $urlParams = [])
    {
        $err = true;
        $message = 'OK';
        $code = 0;
        $body = "";

        $this->request->set('path', $path);
        
        if (count($urlParams))
        {
            $this->request->set('urlParams', http_build_query($urlParams));
        }

        $this->setOption(CURLOPT_URL, $this->request->url());

        $this->request->set('time', time());
        $this->applyHeaders();

        //dd(curl_getinfo($this->client));

        $payload = [];
        
        try
        {
            $requestExec = curl_exec($this->client);

            if ($requestExec === false)
            {
                $message = "HttpClient Error: ".curl_errno($this->client)." ".curl_error($this->client);
                Throw new \Error($message);
            }
            
            $err = false;
            
            $this->response->received($requestExec, $this->client, $this->config->get('write_output_body_types'));
            
            $code = $this->response->get('httpCode');
            $body = $this->response->get('body');
            $payload = [
                'code' => $this->response->get('httpCode'),
                'executionTime' => $this->response->get('meta','executionTime'),
                'headers' => $this->response->get('headers'),
                'body' => $this->response->get('body'),
                'debug' => $this->debug,
            ];
    
            if ($this->debug)
            {
                $payload['request'] = $this->request->all();
                $payload['response'] = $this->response->all();
            }
        }
        catch (\Error $e)
        {
            $message =  $e->getMessage();
        }
        
        curl_close($this->client);

        return [
            'err' => $err,
            'code' => $code,
            'body' => $body,
            'message' => $message,
            'payload' => $payload,
        ];
    }

    public function request(): HttpRequest
    {
        return $this->request;
    }

    public function response(): HttpResponse
    {
        return $this->response;
    }

    public function initOptions():void
    {
        // ipv4 only
        if ($this->config->get('ipv4_only'))
        {
            $this->setOption(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        else
        {
            $this->setOption(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_WHATEVER);
        }

        foreach ($this->config->get('options') as $key => $value)
        {
            $this->setOption($key, $value);
        }
        
    }

    public function initHeaders():void
    {
        $headers = $this->config->get('default_headers');

        foreach ($headers as $key => $value)
        {
            $this->request->set("headers.{$key}", $value);
        }
    }

    public function applyHeaders():void
    {
        $headers = $this->request->get('headers');
        $http_headers = [];
        foreach ($headers as $k => $v)
        {
            $http_headers[] = "{$k}: {$v}";
        }
        $this->setOption(CURLOPT_HTTPHEADER, $http_headers);
    }

    public function setOption(
        int $curl_opt, mixed $value):void
    {
        curl_setopt($this->client, $curl_opt, $value);
    }
}