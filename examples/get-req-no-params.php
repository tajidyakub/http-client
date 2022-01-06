<?php

use Tjd\Http\HttpClient;
use Tjd\Http\HttpConfig;

require __DIR__ .'/../vendor/autoload.php';

dd(HttpClient::request('https://www.kompas.com', new HttpConfig, gethostname())->get()->exec());