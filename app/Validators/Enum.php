<?php

namespace App\Validators;

use Attribute;

#[Attribute]
readonly class Enum
{
    public function __construct(
        private string $enumClass
    )
    {
        if (!enum_exists($this->enumClass)) {
            throw new \InvalidArgumentException("Class {$this->enumClass} is not a valid Enum.");
        }
    }

    public function validate(mixed $value): bool
    {
        return in_array($value, array_column($this->enumClass::cases(), 'value'), true);
    }

}
