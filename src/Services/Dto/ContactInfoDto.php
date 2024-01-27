<?php

namespace App\Services\Dto;

class ContactInfoDto
{
    public function __construct(private array $contactDetails = [])
    {
    }

    public function getContactDetails(): array
    {
        return $this->contactDetails;
    }

    public function addContactDetails(int $type, string $value): self
    {
        $this->contactDetails[] = new ContactDetailsDto($value, $type);

        return $this;
    }

}