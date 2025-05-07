<?php

namespace App\Validators;

use Attribute;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_PROPERTY)]
class PositiveNumber
{
    public function validate(float|int $value): void
    {
        if ($value <= 0) {
            throw new InvalidArgumentException("The value must be a positive number. Given: {$value}");
        }
    }


}