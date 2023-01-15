<?php

declare(strict_types=1);

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;
use Psr\Clock\ClockInterface;

/**
 * @mixin CreatableInterface
 * @psalm-require-implements CreatableInterface
 */
trait Creatable
{
    /**
     * @var \DateTimeImmutable
     */
    #[ORM\Column(name: 'created_at', type: 'datetimetz_immutable', options: [
        'default' => 'CURRENT_TIMESTAMP',
    ])]
    private \DateTimeImmutable $createdAt;

    /**
     * {@inheritDoc}
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt ??= new \DateTimeImmutable();
    }

    /**
     * {@inheritDoc}
     */
    public function wasCreatedAt(\DateTimeInterface|ClockInterface $date): void
    {
        if ($date instanceof ClockInterface) {
            $date = $date->now();
        }

        if ($date instanceof \DateTime) {
            $date = \DateTimeImmutable::createFromMutable($date);
        }

        $this->createdAt = clone $date;
    }
}
