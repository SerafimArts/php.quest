<?php

declare(strict_types=1);

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;
use Psr\Clock\ClockInterface;

/**
 * @mixin UpdatedDateProviderInterface
 * @psalm-require-implements UpdatableInterface
 */
trait Updatable
{
    /**
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(name: 'updated_at', type: 'datetimetz_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * {@inheritDoc}
     */
    public function updatedAt(): \DateTimeImmutable|null
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritDoc}
     */
    public function touch(\DateTimeInterface|ClockInterface $now): void
    {
        if ($now instanceof ClockInterface) {
            $now = $now->now();
        }

        if ($now instanceof \DateTime) {
            $now = \DateTimeImmutable::createFromMutable($now);
        }

        $this->updatedAt = clone $now;
    }
}
