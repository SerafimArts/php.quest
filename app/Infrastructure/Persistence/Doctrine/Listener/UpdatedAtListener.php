<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Listener;

use App\Domain\UpdatedDateProviderInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Clock\ClockInterface;

/**
 * Each object that implements the {@see UpdatedDateProviderInterface} interface before
 * UPDATE data in the database will also update its update date using the system
 * date returned from the interface's {@see ClockInterface} implementation.
 */
final readonly class UpdatedAtListener
{
    public function __construct(
        private ClockInterface $clock,
    ) {
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        $target = $event->getObject();

        if ($target instanceof UpdatedDateProviderInterface) {
            $target->touch($this->clock->now());
        }
    }
}
