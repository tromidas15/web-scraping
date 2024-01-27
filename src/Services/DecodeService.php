<?php

namespace App\Services;

class DecodeService
{
    public function decodeEmail(string $obfuscatedEmail): string
    {
        $reversedEmail = strrev($obfuscatedEmail);
        $decodedEmail = str_replace([']ta[', ']tod['], ['@', '.'], $reversedEmail);

        return $decodedEmail;
    }
}