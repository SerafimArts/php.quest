<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Subscriber;

use App\Domain\Documentation\PrerenderedContentInterface;
use App\Domain\ProvidesContentInterface;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Local\ContentRenderer\ContentRendererInterface;

final readonly class ContentRenderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ContentRendererInterface $renderer,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist => 'prePersist',
            Events::preUpdate  => 'preUpdate',
        ];
    }

    public function prePersist(PrePersistEventArgs $events): void
    {
        $this->render($events->getObject());
    }

    private function render(object $entity): void
    {
        if ($entity instanceof ProvidesContentInterface) {
            $content = $entity->getContent();

            if ($content instanceof PrerenderedContentInterface && !$content->isRendered()) {
                $content->render($this->renderer);
            }
        }
    }

    public function preUpdate(PreUpdateEventArgs $events): void
    {
        $this->render($events->getObject());
    }
}
