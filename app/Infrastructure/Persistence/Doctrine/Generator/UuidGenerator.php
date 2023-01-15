<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Generator;

use App\Domain\Shared\IdentifiableInterface;
use App\Domain\Shared\UuidIdentifier;
use App\Domain\Shared\IdInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

final class UuidGenerator extends AbstractIdGenerator
{
    /**
     * @param ClockInterface $clock
     */
    public function __construct(
        private readonly ClockInterface $clock = new NativeClock(),
    ) {
    }

    /**
     * Generate an identifier
     *
     * @param EntityManager $em
     * @param object $entity
     *
     * @return IdInterface
     */
    public function generate(EntityManager $em, $entity): IdInterface
    {
        if ($entity instanceof IdentifiableInterface) {
            return $entity->getId();
        }

        return UuidIdentifier::fromDate($this->clock->now());
    }
}
