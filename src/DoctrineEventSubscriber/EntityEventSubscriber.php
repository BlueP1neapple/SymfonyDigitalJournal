<?php

namespace JoJoBizzareCoders\DigitalJournal\DoctrineEventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

/**
 * Подписчик на связанные события с сущностями
 */
class EntityEventSubscriber implements EventSubscriber
{

    /**
     * Логгер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [Events::postLoad];
    }

    /**
     * Обработчик события загрузки сущности
     *
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function postLoad(LifecycleEventArgs $args):void
    {
        $this->logger->debug('Event postLoad:' . get_class($args->getEntity()));
    }

}