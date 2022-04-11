<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;

/**
 * dto объект с информацией о Фио пользователя
 */
final class FioDto
{
    //Свойства
    /**
     * Фамилия пользователя
     *
     * @var string
     */
    private string $surname;

    /**
     * Имя пользователя
     *
     * @var string
     */
    private string $name;

    /**
     * Отчество пользователя
     *
     * @var string
     */
    private string $patronymic;

    //Методы

    /**
     * Конструктор dto объект с информацией о Фио пользователя
     *
     * @param string $surname - Фамилия пользователя
     * @param string $name - Имя пользователя
     * @param string $patronymic - Отчество пользователя
     */
    public function __construct(string $surname, string $name, string $patronymic)
    {
        $this->surname = $surname;
        $this->name = $name;
        $this->patronymic = $patronymic;
    }

    /**
     * Возвращает фамилия пользователя
     *
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * Возвращает имя пользователя
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Возвращает отчество пользователя
     *
     * @return string
     */
    public function getPatronymic(): string
    {
        return $this->patronymic;
    }
}