<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

/**
 * Интерфейс репризитория занятий
 */
interface LessonRepositoryInterface
{

    /**
     * поиск сущностей занятий по заданным критериям
     *
     * @param array $criteria - заданные критерии поиска занятий
     * @return array
     */
    public function findBy(array $criteria):array;

    /**
     * Получить id
     *
     * @return int
     */
    public function nextId():int;

    /**
     * Добавить урок
     *
     * @param LessonClass $entity
     * @return LessonClass
     */
    public function add(LessonClass $entity): LessonClass;
}