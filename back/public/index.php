<?php

$loader = require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$app = new ChatApp\Bootstrap\Core();
$app->map('/', function () {
    return new Response('This is the home page');
});
$app->map('/about', function () {
    return new Response('This is the about page');
});
$app->map('/chat/{max}', 'ChatController@index');
$response = $app->handle($request);
$response->send();
