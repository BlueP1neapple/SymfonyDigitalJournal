<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

use Doctrine\ORM\EntityRepository;

interface TeacherRepositoryInterface extends AbstractUserRepositoryInterface
{

    /**
     * Поиск сущностей по критериям
     *
     * @param array $criteria
     * @return array
     */
    public function findBy(array $criteria): array;

    public function findUserByLogin(string $login): ?AbstractUserClass;

}