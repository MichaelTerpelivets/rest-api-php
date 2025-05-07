<?php

/**
 * Data Transfer Object (DTO) for representing product details.
 *
 * This DTO is designed to encapsulate the relevant data for a product
 * including its name, price, category, and additional attributes.
 *
 * @property-read string $name The name of the product.
 * @property-read float $price The price of the product.
 * @property-read string $category The category to which the product belongs.
 * @property-read array $attributes Additional attributes of the product, such as specifications or metadata.
 */

namespace App\DTO;

use App\Enums\Category;
use App\Validators\Enum;
use App\Validators\NotBlank;
use App\Validators\PositiveNumber;

/**
 * Represents a Data Transfer Object for a product containing its name, price, category, and additional attributes.
 */
readonly class ProductDTO
{
    public function __construct(
        public ?string                         $id,
        #[NotBlank] public string              $name,
        #[PositiveNumber] public float         $price,
        #[Enum(Category::class)] public string $category,
        public array                           $attributes = []
    )
    {
    }
}