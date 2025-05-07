<?php

use App\Services\ProductService;
use App\Http\Controllers\ProductController;
use Implementations\PostgresProductRepository;

$dbConfig = require __DIR__ . '/../config/database.php';

$pdo = new PDO(
    "pgsql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']}",
    $dbConfig['username'],
    $dbConfig['password']
);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$repository = new PostgresProductRepository($pdo);
$service = new ProductService($repository);
$controller = new ProductController($service);

return $controller;