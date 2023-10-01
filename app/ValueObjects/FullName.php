<?php

namespace App\ValueObjects;

use InvalidArgumentException;

final class FullName
{
    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
    ) {
    }

    public static function fromString(string $fullName): self
    {
        $parts = explode(' ', $fullName);

        if (count($parts) < 2) {
            throw new InvalidArgumentException('It is not valid full name');
        }

        return new self(
            firstName: $parts[0],
            lastName: $parts[1],
        );
    }

    public function toString(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
