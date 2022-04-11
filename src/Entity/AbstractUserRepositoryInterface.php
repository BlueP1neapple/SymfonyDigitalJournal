<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\ORM\EntityRepository;

interface AbstractUserRepositoryInterface extends ServiceEntityRepositoryInterface
{
    /**
     * Поиск
     *
     * @param array $criteria
     * @return array
     */
    public function findBy(array $criteria): array;

    /**
     * Поиск по предметам
     *
     * @param string $login
     * @return AbstractUserClass|null
     */
    public function findUserByLogin(string $login): ?AbstractUserClass;
}
