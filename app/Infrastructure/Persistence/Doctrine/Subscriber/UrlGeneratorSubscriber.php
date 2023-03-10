<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Subscriber;

use App\Domain\NameProviderInterface;
use App\Domain\UrlProviderInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

final readonly class UrlGeneratorSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate  => 'preUpdate',
            Events::prePersist => 'prePersist',
        ];
    }

    public function prePersist(PrePersistEventArgs $event): void
    {
        $this->regenerate($event);
    }

    private function regenerate(LifecycleEventArgs $event): void
    {
        $target = $event->getObject();

        if ($target instanceof UrlProviderInterface && $target instanceof NameProviderInterface) {
            $url = $this->slugger->slug($target->getTitle());

            $target->setUrl($url);
        }
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $this->regenerate($event);
    }
}
