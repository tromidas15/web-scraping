<?php

namespace App\Services\Dto;

class ContactDetailsDto
{
    public function __construct(public readonly string $value, public readonly int $type)
    {
    }
}