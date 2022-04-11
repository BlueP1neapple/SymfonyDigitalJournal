<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use Doctrine\ORM\EntityRepository;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemRepositoryInterface;

/**
 * Репозиторий сущностей предметов, работающий с доктриной
 */
class ItemDoctrineRepository extends EntityRepository implements ItemRepositoryInterface
{
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function nexId(): int
    {
        return $this->getClassMetadata()->idGenerator->generateId(
            $this->getEntityManager(),
            null
        );
    }

    /**
     * @inheritDoc
     */
    public function add(ItemClass $entity): ItemClass
    {
        $this->getEntityManager()->persist($entity);
        return $entity;
    }
}
