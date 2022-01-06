<?php

use Tjd\Http\HttpClient;
use Tjd\Http\HttpConfig;

require __DIR__ . '/../vendor/autoload.php';

$keys = "headers.Accept";

dd(HttpClient::request(
    'https://cbn.net.id', new HttpConfig, gethostname())
    ->debug()
    ->get()
    ->exec());