<?php


declare(strict_types=1);

header('Content-Type: application/json');

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

require_once __DIR__ . '/../vendor/autoload.php';

set_exception_handler(function (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $e->getMessage(),
    ]);
});

set_error_handler(function ($severity, $message, $file, $line) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Runtime Error',
        'message' => $message,
        'file' => $file,
        'line' => $line,
    ]);
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Fatal Error',
            'message' => $error['message'],
        ]);
    }
});

$dispatcher = require __DIR__ . '/../routes/api.php';

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

$request = $creator->fromGlobals();
$httpMethod = $request->getMethod();
$uri = $request->getUri()->getPath();

$routeInfo = $dispatcher->dispatch($httpMethod, rawurldecode($uri));

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        (new Symfony\Component\HttpFoundation\Response('Not Found', 404))->send();
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        (new Symfony\Component\HttpFoundation\Response('Method Not Allowed', 405))->send();
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $response = $handler($request, $psr17Factory->createResponse(), $vars);
        (new Laminas\HttpHandlerRunner\Emitter\SapiEmitter())->emit($response);
        break;
}