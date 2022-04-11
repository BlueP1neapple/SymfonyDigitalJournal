<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchTeacherService;

use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService\ItemDto;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;

final class SearchTeacherCriteria
{
    /**
     * ид преподователя
     *
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Фио - преподавателя
     *
     * @var Fio[]
     */
    private ?array $fio = null;

    /**
     * Дата рождения преподавателя
     *
     * @var string|null
     */
    private ?string $dateOfBirth = null;

    /**
     * Номер телефона преподавателя
     *
     * @var string|null
     */
    private ?string $phone = null;

    /**
     * Адресс проживания преподователя
     *
     * @var Address[]
     */
    private ?array $address = null;

    /**
     * id предмета Преподавателя
     *
     * @var ItemDto|null
     */
    private ?ItemDto $item = null;

    /**
     * Кабинет преподавателя
     *
     * @var int|null
     */
    private ?int $cabinet = null;

    /**
     * email Преподавателя
     *
     * @var string|null
     */
    private ?string $email = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): SearchTeacherCriteria
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Fio[]
     */
    public function getFio(): ?array
    {
        return $this->fio;
    }

    /**
     * @param Fio[] $fio
     */
    public function setFio(?array $fio): SearchTeacherCriteria
    {
        $this->fio = $fio;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    /**
     * @param string|null $dateOfBirth
     */
    public function setDateOfBirth(?string $dateOfBirth): SearchTeacherCriteria
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): SearchTeacherCriteria
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return Address[]
     */
    public function getAddress(): ?array
    {
        return $this->address;
    }

    /**
     * @param Address[] $address
     */
    public function setAddress(?array $address): SearchTeacherCriteria
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return ItemDto|null
     */
    public function getItem(): ?ItemDto
    {
        return $this->item;
    }

    /**
     * @param ItemDto|null $item
     */
    public function setItem(?ItemDto $item): SearchTeacherCriteria
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCabinet(): ?int
    {
        return $this->cabinet;
    }

    /**
     * @param int|null $cabinet
     */
    public function setCabinet(?int $cabinet): SearchTeacherCriteria
    {
        $this->cabinet = $cabinet;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): SearchTeacherCriteria
    {
        $this->email = $email;
        return $this;
    }


}