<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\NewItemService;

class ResultRegisteringItemDto
{
    /**
     * id предмета
     *
     * @var int
     */
    private int $id;

    /**
     * @param int $id - id предмета
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Возвращает id предмета
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}