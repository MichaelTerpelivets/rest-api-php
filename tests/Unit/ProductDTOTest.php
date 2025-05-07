<?php

use PHPUnit\Framework\TestCase;
use App\DTO\ProductDTO;

class ProductDTOTest extends TestCase
{
    public function testCreateDTO()
    {
        $dto = new ProductDTO("MacBook", 1500.00, "electronics", ["brand" => "Apple"]);
        $this->assertEquals("MacBook", $dto->name);
    }
}