<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchTeacherService;

class SearchTeacherServiceCriteria
{
    /**
     * id Учителя
     *
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Возвращать id учителя
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Устанавливает id учителя
     *
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

}