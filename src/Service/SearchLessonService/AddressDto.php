<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;

/**
 * Dto объект с информацией о адрессе пользователя
 */
final class AddressDto
{
    //Свойства
    /**
     * улица пользователя
     *
     * @var string
     */
    private string $street;

    /**
     * номер дома пользователя
     *
     * @var string
     */
    private string $home;

    /**
     * номер квартиры пользователя
     *
     * @var string
     */
    private string $apartment;

    //Методы

    /**
     * Конструктор Dto объект с информацией о адрессе пользователя
     *
     * @param string $street - улица пользователя
     * @param string $home - номер дома пользователя
     * @param string $apartment - номер квартиры пользователя
     */
    public function __construct(string $street, string $home, string $apartment)
    {
        $this->street = $street;
        $this->home = $home;
        $this->apartment = $apartment;
    }

    /**
     * Возвращает улица пользователя
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * Возвращает номер дома пользователя
     *
     * @return string
     */
    public function getHome(): string
    {
        return $this->home;
    }

    /**
     * Возвращает номер квартиры пользователя
     *
     * @return string
     */
    public function getApartment(): string
    {
        return $this->apartment;
    }
}