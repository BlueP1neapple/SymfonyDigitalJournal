<?php

    namespace JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;

    /**
     * DTO объект специфицирующий результаты работы сервиса Класса
     */
class ClassDto
{
    /**
     * id Класса
     *
     * @var int
     */
    private int $id;

    /**
     * Номер класса
     *
     * @var int
     */
    private int $number;

    /**
     * Буква класса
     *
     * @var string
     */
    private string $letter;


    /**
     * Конструктор dto объекта класса
     *
     * @param int $id - id класса
     * @param int $number - номер класса
     * @param string $letter - буква класса
     */
    public function __construct(int $id, int $number, string $letter)
    {
        $this->id = $id;
        $this->number = $number;
        $this->letter = $letter;
    }

    /**
     * Возвращает id класса
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает Номер класса
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Возвращает Букву класса
     *
     * @return string
     */
    public function getLetter(): string
    {
        return $this->letter;
    }
}
