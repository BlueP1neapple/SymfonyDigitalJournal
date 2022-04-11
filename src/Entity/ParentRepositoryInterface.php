<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

interface ParentRepositoryInterface extends AbstractUserRepositoryInterface
{

    /**
     * Поиск сущностей по заданным критериям
     *
     * @param array $criteria - заданные критерия
     * @return array
     */
    public function findBy(array $criteria): array;

    public function findUserByLogin(string $login): ?AbstractUserClass;

}