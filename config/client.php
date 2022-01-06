<?php

return [
    'version' => '0.0.1',

    'ipv4_only' => true,

    'options' => [
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_AUTOREFERER => true,
        CURLINFO_HEADER_OUT => true,
        CURLOPT_COOKIEJAR => 'cookie.txt'
        // CURLOPT_TCP_FASTOPEN => true,
        // CURLOPT_TCP_KEEPALIVE => 1,	
        // CURLOPT_DNS_SERVERS => '127.0.0.1,1.1.1.1,1.0.0.1',
        // CURLOPT_ACCEPT_ENCODING => ' ',
    ],
    'content_types' => [
        'json' => 'application/json',
        'xml' => 'application/xml',
        'form' => 'application/x-www-form-urlencoded',
    ],
    'default_headers' => [
        'Accept' => '*/*',
        //'User-Agent' => "Tjd\Http\HttpClient https://github.com/tajidyakub/http-client.git",
        'User-Agent' => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:95.0) Gecko/20100101 Firefox/95.0",
        'Connection' => 'keep-alive',
    ],
    'write_output_body_types' => [
        'text/html',
        'application/json',
        'application/xml',
        'image/svg',
    ],
];