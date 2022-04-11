<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use JoJoBizzareCoders\DigitalJournal\Entity\AbstractUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\TeacherRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;

class UserDoctrineRepository extends EntityRepository implements
    ParentRepositoryInterface,
    StudentRepositoryInterface,
    TeacherRepositoryInterface
{

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(AbstractUserClass::class));
    }


    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function findUserByLogin(string $login): ?AbstractUserClass
    {
        $entities = $this->findBy(['login' => $login]);
        $countEntities = count($entities);

        if ($countEntities > 1) {
            throw new RuntimeException('Найдены пользователи с дублирующимися логинами');
        }

        return 0 === $countEntities ? null : current($entities);
    }
}
