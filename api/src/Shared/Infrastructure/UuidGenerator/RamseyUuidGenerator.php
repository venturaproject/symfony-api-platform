<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\UuidGenerator;

use App\Shared\Domain\UuidGenerator\UuidGeneratorInterface;
use Ramsey\Uuid\Uuid;

class RamseyUuidGenerator implements UuidGeneratorInterface
{
    static function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}