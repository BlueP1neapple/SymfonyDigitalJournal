<?php

    namespace JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;

    use DateTimeImmutable;

    /**
     * DTO объект специфицирующий результаты работы сервиса Учителей
     */
final class TeacherDto
{
    /**
     * ид преподователя
     *
     * @var int
     */
    private int $id;

    /**
     * Фио - преподавателя
     *
     * @var array
     */
    private array $fio;

    /**
     * Дата рождения преподавателя
     *
     * @var string
     */
    private string $dateOfBirth;

    /**
     * Номер телефона преподавателя
     *
     * @var string
     */
    private string $phone;

    /**
     * Адресс проживания преподователя
     *
     * @var AddressDto
     */
    private AddressDto $address;

    /**
     * id предмета Преподавателя
     *
     * @var ItemDto
     */
    private ItemDto $item;

    /**
     * Кабинет преподавателя
     *
     * @var int
     */
    private int $cabinet;

    /**
     * email Преподавателя
     *
     * @var string
     */
    private string $email;

    /**
     * Конструктор DTO объект специфицирующий результаты работы сервиса Учителей
     *
     * @param int $id - ид преподователя
     * @param array $fio - Фио - преподавателя
     * @param string $dateOfBirth - Дата рождения преподавателя
     * @param string $phone - Номер телефона преподавателя
     * @param AddressDto $address - Адресс проживания преподователя
     * @param ItemDto $item - id предмета Преподавателя
     * @param int $cabinet - Кабинет преподавателя
     * @param string $email - email Преподавателя
     */
    public function __construct(
        int $id,
        array $fio,
        string $dateOfBirth,
        string $phone,
        AddressDto $address,
        ItemDto $item,
        int $cabinet,
        string $email
    ) {
        $this->id = $id;
        $this->fio = $fio;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
        $this->address = $address;
        $this->item = $item;
        $this->cabinet = $cabinet;
        $this->email = $email;
    }

    /**
     * Возвращает ид преподователя
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает Фио - преподавателя
     *
     * @return array
     */
    public function getFio(): array
    {
        return $this->fio;
    }

    /**
     * Возвращает Дата рождения преподавателя
     *
     * @return string
     */
    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    /**
     * Возвращает Номер телефона преподавателя
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Возвращает Адресс проживания преподователя
     *
     * @return AddressDto
     */
    public function getAddress(): AddressDto
    {
        return $this->address;
    }

    /**
     * Возвращает id предмета Преподавателя
     *
     * @return ItemDto
     */
    public function getItem(): ItemDto
    {
        return $this->item;
    }

    /**
     * Возвращает Кабинет преподавателя
     *
     * @return int
     */
    public function getCabinet(): int
    {
        return $this->cabinet;
    }

    /**
     * Возвращает email Преподавателя
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
