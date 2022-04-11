<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchClassService;

final class SearchClassServiceCriteria
{
    /**
     * Id класса
     *
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Возвращает id класса
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Устанавливает id класса
     *
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

}