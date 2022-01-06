<?php
namespace Tjd\Http;

use CurlHandle;

class HttpResponse
{
    protected bool $debug = false;

    protected HttpRequest $request;

    protected array $headers;
    
    protected string $body = '';

    protected int $httpCode;

    protected array $meta;

    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    public function debug(): self
    {
        $this->debug = true;
        return $this;
    }

    public function received(
        $requestExec, 
        CurlHandle $client,
        array $writeOutputTypes)
    {
        $this->meta = curl_getinfo($client);
        $this->httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        $this->meta['executionTime'] = curl_getinfo($client, CURLINFO_TOTAL_TIME);
        $this->setBody($client, $requestExec, $writeOutputTypes);
    }

    protected function setHeaders(string $header_string)
    {
        // Convert the $headers string to an indexed array
        $headers_indexed_arr = explode("\r\n", $header_string);

        // Define as array before using in loop
        $headers_arr = array();
        // Remember the status message in a separate variable
        $status_message = array_shift($headers_indexed_arr);

        // Create an associative array containing the response headers
        foreach ($headers_indexed_arr as $value) {
            if(false !== ($matches = explode(':', $value, 2))) {
                if (isset($matches[0]) && isset($matches[1]))
                {
                    $headers_arr["{$matches[0]}"] = trim($matches[1]);
                }
            }                
        }

        $this->headers = $headers_arr;
    }

    protected function setBody(CurlHandle $client, $requestExec, $writeOutputTypes)
    {

        $header_len = curl_getinfo($client, CURLINFO_HEADER_SIZE);
        $header_string = substr($requestExec, 0, $header_len);
        
        $this->setHeaders($header_string);

        if (isset($this->headers['content-type']))
        {
            $exp = explode(";", $this->headers['content-type']);
            
            $needle = trim($exp[0]);
            
            if (in_array($needle, $writeOutputTypes))
            {
                $this->body = substr($requestExec, $header_len);
            }
            else
            {
                $this->body = "Output is not recorded - change this through Configuration Object.";
            }
        }
    }

    public function all()
    {
        return [
            'code' => $this->httpCode,
            'meta' => $this->meta,
            'body' => $this->body,
            'headers' => $this->headers,
        ];
    }

    public function get(
        string $props,
        string|null $key = null): mixed
    {
        return $key ? $this->{$props}[$key] : $this->{$props};
    }
}