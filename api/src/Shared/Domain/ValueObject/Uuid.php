<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

class Uuid
{
    private string $uuid;
    private const UUID_REGEX = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

    public function __construct(string $uuid)
    {
        if (!$this->isValidUuid($uuid)) {
            throw new \InvalidArgumentException("Invalid UUID: $uuid");
        }

        $this->uuid = $uuid;
    }

    private function isValidUuid(string $uuid): bool
    {
        return (bool) preg_match(self::UUID_REGEX, $uuid); // Cast to bool
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

     /**
     * Returns the UUID as a string.
     */
    
    public function toString(): string
    {
        return $this->uuid;
    }
}
