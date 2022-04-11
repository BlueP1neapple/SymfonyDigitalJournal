<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use Doctrine\ORM\EntityRepository;
use JoJoBizzareCoders\DigitalJournal\Entity\ClassRepositoryInterface;

class ClassDoctrineRepository extends EntityRepository implements ClassRepositoryInterface
{
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}
