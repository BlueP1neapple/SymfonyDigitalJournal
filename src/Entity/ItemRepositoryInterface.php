<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

interface ItemRepositoryInterface
{
    /**
     * Поиск сущностей по критериям
     *
     * @param array $criteria
     * @return ItemClass[]
     */
    public function findBy(array $criteria):array;
}