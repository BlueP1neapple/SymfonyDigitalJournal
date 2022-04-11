<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\NewItemService;

class NewItemDto
{
    /**
     * Название предмета
     *
     * @var string
     */
    private string $name;

    /**
     * Расщифровка названия предмета
     *
     * @var string
     */
    private string $description;

    /**
     * @param string $name - Название предмета
     * @param string $description - Расщифровка названия предмета
     */
    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Возвращает Название предмета
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает Расщифровка названия предмета
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

}