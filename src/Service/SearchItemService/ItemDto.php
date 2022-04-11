<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchItemService;

final class ItemDto
{
    /**
     * id предметов
     *
     * @var int
     */
    private int $id;

    /**
     * Название предметов
     *
     * @var string
     */
    private string $name;

    /**
     * Расщифровка названия предметов
     *
     * @var string
     */
    private string $description;


    /**
     * конструктор DTO объект специфицирующий результаты работы сервиса Предметов
     *
     * @param int $id - id предметов
     * @param string $name - Название предметов
     * @param string $description - Расщифровка названия предметов
     */
    public function __construct(int $id, string $name, string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Возвращает id предметов
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает Название предметов
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает Расщифровка названия предметов
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}