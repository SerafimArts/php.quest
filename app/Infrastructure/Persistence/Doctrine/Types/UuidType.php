<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Types;

use App\Domain\Shared\IdInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

abstract class UuidType extends Type
{
    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return static::getClass();
    }

    /**
     * @return class-string<IdInterface>
     */
    abstract protected static function getClass(): string;

    /**
     * {@inheritDoc}
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'UUID';
    }

    /**
     * @param IdInterface $value
     * @param AbstractPlatform $platform
     *
     * @return non-empty-string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string|null
    {
        if (\is_string($value) || $value instanceof \Stringable) {
            return (string)$value;
        }

        return null;
    }

    /**
     * @param non-empty-string|null $value
     * @param AbstractPlatform $platform
     *
     * @return IdInterface|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?IdInterface
    {
        if ($value === null) {
            return null;
        }

        $class = static::getClass();

        return new $class($value);
    }
}
