<?php

namespace App\Validators;

use Attribute;

#[Attribute]
class NotBlank
{
    public function __construct(public string $message = 'Cannot be blank.')
    {
    }
}