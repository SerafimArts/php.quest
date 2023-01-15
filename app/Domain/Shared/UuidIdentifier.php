<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

readonly class UuidIdentifier implements IdInterface, ConvertableToUuidInterface, \JsonSerializable
{
    /**
     * @var non-empty-string
     */
    private string $value;

    /**
     * @param non-empty-string|\Stringable $scalar
     */
    final public function __construct(string|\Stringable $scalar)
    {
        $this->value = (string)$scalar;
    }

    /**
     * @param non-empty-string|null $namespace
     *
     * @return static
     */
    public static function new(string $namespace = null): static
    {
        if ($namespace) {
            return static::fromNamespace($namespace);
        }

        return new static(Uuid::uuid4());
    }

    /**
     * @param non-empty-string $namespace
     *
     * @return static
     */
    public static function fromNamespace(string $namespace): static
    {
        return new static(Uuid::uuid5(Uuid::uuid4(), $namespace));
    }

    /**
     * @param non-empty-string $value
     *
     * @return static
     */
    public static function fromString(string $value): static
    {
        return new static(Uuid::fromString($value));
    }

    /**
     * @param \DateTimeInterface $date
     *
     * @return static
     */
    public static function fromDate(\DateTimeInterface $date): static
    {
        return new static(Uuid::uuid7($date));
    }

    /**
     * @return static
     */
    public static function nil(): static
    {
        return new static(Uuid::NIL);
    }

    /**
     * @return static
     */
    public static function max(): static
    {
        return new static(Uuid::MAX);
    }

    /**
     * @return UuidInterface
     */
    public function toUuid(): UuidInterface
    {
        return Uuid::fromString($this->value);
    }

    /**
     * {@inheritDoc}
     */
    public function same(ValueObjectInterface $object): bool
    {
        return $this->value === (string)$object;
    }

    /**
     * @return non-empty-string
     */
    public function jsonSerialize(): string
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
