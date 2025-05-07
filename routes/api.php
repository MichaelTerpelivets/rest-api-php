<?php

use FastRoute\RouteCollector;

$controller = require __DIR__ . '/../bootstrap/container.php';

return FastRoute\simpleDispatcher(function (RouteCollector $r) use ($controller) {
    $r->addRoute('GET', '/products', [$controller, 'list']);
    $r->addRoute('POST', '/products', [$controller, 'create']);
    $r->addRoute('GET', '/products/{id}', [$controller, 'get']);
    $r->addRoute('PATCH', '/products/{id}', [$controller, 'update']);
    $r->addRoute('DELETE', '/products/{id}', [$controller, 'delete']);
});

