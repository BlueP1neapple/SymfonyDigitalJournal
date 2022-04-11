<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

/**
 * Интерфейс репризитория студентов
 */
interface StudentRepositoryInterface extends AbstractUserRepositoryInterface

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
