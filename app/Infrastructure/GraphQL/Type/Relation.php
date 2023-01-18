<?php

declare(strict_types=1);

namespace App\Infrastructure\GraphQL\Type;

/**
 * @template TValue of mixed
 * @template-implements \IteratorAggregate<array-key, TValue>
 */
final class Relation implements \IteratorAggregate, \Countable
{
    /**
     * @var array<array-key, TValue>
     */
    private readonly array $resolved;

    /**
     * @var iterable<array-key, TValue>|null
     */
    private ?iterable $data;

    /**
     * @param iterable<array-key, TValue> $data
     */
    protected function __construct(
        iterable $data,
    ) {
        $this->data = $data;

        if (\is_array($data)) {
            $this->data = null;
            $this->resolved = $data;
        }
    }

    /**
     * @template TDataValue of mixed
     *
     * @param iterable<array-key, TDataValue> $data
     *
     * @return self<TDataValue>
     */
    public static function fromIterable(iterable $data): self
    {
        if ($data instanceof self) {
            return $data;
        }

        return new self($data);
    }

    /**
     * @template TDataValue of mixed
     *
     * @param callable():iterable<array-key, TDataValue> $iterable
     *
     * @return self<TDataValue>
     */
    public static function fromCallable(callable $iterable): self
    {
        return self::fromIterable($iterable());
    }

    /**
     * @param callable(TValue,array-key):bool $predicate
     *
     * @return $this
     */
    public function filter(callable $predicate): self
    {
        return self::fromCallable(function () use ($predicate): iterable {
            foreach ($this as $key => $item) {
                if ($predicate($item, $key)) {
                    yield $key => $item;
                }
            }
        });
    }

    /**
     * @param callable(TValue,array-key):TValue $transform
     *
     * @return $this
     */
    public function map(callable $transform): self
    {
        return self::fromCallable(function () use ($transform): iterable {
            foreach ($this as $key => $item) {
                yield $transform($item, $key);
            }
        });
    }

    /**
     * @return array<array-key, TValue>
     */
    public function toArray(): array
    {
        $this->resolve();

        return $this->resolved;
    }

        /**
     * @return \Traversable<array-key, TValue>
     */
    public function getIterator(): \Traversable
    {
        $this->resolve();

        return new \ArrayIterator($this->resolved);
    }

    /**
     * @return void
     */
    private function resolve(): void
    {
        if ($this->data !== null) {
            $this->resolved = [...$this->data];
            $this->data = null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        $this->resolve();

        return \count($this->resolved);
    }
}
