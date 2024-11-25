<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\Types;

use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class UuidValueObjectType extends GuidType
{
    public const NAME =  'uuid_value_object';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
    {
        if ($value === null || $value instanceof Uuid) {
            return $value;
        }

        return new Uuid($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null || $value instanceof Uuid) {
            return (string) $value;
        }

        return $value->__toString();
    }
}