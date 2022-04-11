<?php

    namespace JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService;

    use JoJoBizzareCoders\DigitalJournal\ValueObject\Address;
    use JoJoBizzareCoders\DigitalJournal\ValueObject\Fio;

    /**
     * Dto объект с информацией о Студенты
     */
final class StudentDto
{
    /**
     * id студента
     *
     * @var int
     */
    private int $id;

    /**
     * ФИО студента
     *
     * @var array
     */
    private array $fio;

    /**
     * Дата рождения студента
     *
     * @var string
     */
    private string $dateOfBirth;

    /**
     * Номер телефона студента
     *
     * @var string
     */
    private string $phone;

    /**
     * Адресс студента
     *
     * @var Address[]
     */
    private array $address;

    /**
     * Dto объект с информацией о классах
     *
     * @var ClassDto
     */
    private ClassDto $class;

    /**
     * Родители студента
     *
     * @var ParentDto[]
     */
    private array $parents;


    /**
     * Конструктор Dto объект с информацией о Студенты
     *
     * @param int $id - id студента
     * @param Fio[] $fio - ФИО студента
     * @param string $dateOfBirth - дата рождения студента
     * @param string $phone - номер телефона студента
     * @param array $address - адресс продивания студента
     * @param ClassDto $class - класс студента
     * @param array $parent - родители студента
     */
    public function __construct(
        int $id,
        array $fio,
        string $dateOfBirth,
        string $phone,
        array $address,
        ClassDto $class,
        array $parent
    ) {
        $this->id = $id;
        $this->fio = $fio;
        $this->dateOfBirth = $dateOfBirth;
        $this->phone = $phone;
        $this->address = $address;
        $this->class = $class;
        $this->parents = $parent;
    }

    /**
     * Возвращает id студента
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает Фио студента
     *
     * @return array
     */
    public function getFio(): array
    {
        return $this->fio;
    }

    /**
     * Возвращает дату рождения студента
     *
     * @return string
     */
    public function getDateOfBirth(): string
    {
        return $this->dateOfBirth;
    }

    /**
     * Возвращает номер телефона
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Возвращает аддресс проживания студента
     *
     * @return array
     */
    public function getAddress(): array
    {
        return $this->address;
    }

    /**
     * Возвращает класс студента
     *
     * @return ClassDto
     */
    public function getClass(): ClassDto
    {
        return $this->class;
    }

    /**
     * Возвращает dto объект с информацией о родителях
     *
     * @return ParentDto[]
     */
    public function getParents(): array
    {
        return $this->parents;
    }
}
