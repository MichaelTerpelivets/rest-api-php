<?php

namespace App\Validators;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Validate
{
    public function __construct(public string $type)
    {
    }
}