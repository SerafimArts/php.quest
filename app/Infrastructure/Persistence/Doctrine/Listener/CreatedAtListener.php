<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Listener;

use App\Domain\CreatableInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Clock\ClockInterface;

/**
 * Each object that implements the {@see CreatableInterface} interface will
 * force the creation date to be initialized using the system date returned
 * from the {@see ClockInterface} implementation of the interface before
 * SAVING data to the database.
 */
final readonly class CreatedAtListener
{
    public function __construct(
        private ClockInterface $clock,
    ) {
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $target = $event->getObject();

        if ($target instanceof CreatableInterface) {
            $target->wasCreatedAt($this->clock->now());
        }
    }
}
