<?php

namespace JoJoBizzareCoders\DigitalJournal\Entity;

/**
 * Интерфейс репризитория оценок
 */
interface AssessmentReportRepositoryInterface
{
    /**
     * поиск сущностей занятий по заданным критериям
     *
     * @param array $criteria - заданные критерии поиска занятий
     * @return array
     */
    public function findBy(array $criteria): array;

    /**
     * Получить id
     *
     * @return int
     */
    public function nextId(): int;

    /**
     * Сохранить сущность занятия
     *
     * @param ReportClass $entity - сущность занятия
     * @return ReportClass
     */
    public function save(ReportClass $entity): ReportClass;
}
