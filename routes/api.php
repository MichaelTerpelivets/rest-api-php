<?php

use App\Services\ProductService;
use FastRoute\RouteCollector;
use App\Http\Controllers\ProductController;
use Implementations\PostgresProductRepository;

$pdo = new PDO('pgsql:host=db;port=5432;dbname=market', 'john', 'qwerty123');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$productRepository = new PostgresProductRepository($pdo);
$productService = new ProductService($productRepository);
$productController = new ProductController($productService);


return FastRoute\simpleDispatcher(function (RouteCollector $r) use ($productController) {
    $r->addRoute('GET', '/products', function ($request, $response) use ($productController) {
        return $productController->list($request, $response);
    });

    $r->addRoute('POST', '/products', function ($request, $response) use ($productController) {
        return $productController->create($request, $response);
    });

    $r->addRoute('GET', '/products/{id}', function ($request, $response, $args) use ($productController) {
        return $productController->get($request, $response, $args);
    });

    $r->addRoute('PATCH', '/products/{id}', function ($request, $response, $args) use ($productController) {
        return $productController->update($request, $response, $args);
    });

    $r->addRoute('DELETE', '/products/{id}', function ($request, $response, $args) use ($productController) {
        return $productController->delete($request, $response, $args);
    });
});

