<?php

declare(strict_types=1);

namespace App\Shared\Domain\UuidGenerator;

interface UuidGeneratorInterface
{
    static function generate(): string;
}