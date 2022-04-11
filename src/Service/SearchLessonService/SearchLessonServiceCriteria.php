<?php

    namespace JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;

final class SearchLessonServiceCriteria
{
    /**
     * id занятий
     *
     * @var string|null
     */
    private ?string $id = null;

    /**
     * Название предмета
     *
     * @var string|null
     */
    private ?string $itemName = null;

    /**
     * Расщифровка названия предмета
     *
     * @var string|null
     */
    private ?string $itemDescription = null;

    /**
     * Дата проведения занятий
     *
     * @var string|null
     */
    private ?string $date = null;

    /**
     * Фамилия преподавателя
     *
     * @var string|null
     */
    private ?string $teacherSurname = null;

    /**
     * Имя преподавателя
     *
     * @var string|null
     */
    private ?string $teacherName = null;

    /**
     * Отчество преподавателя
     *
     * @var string|null
     */
    private ?string $teacherPatronymic = null;

    /**
     * Кабинет преподавателя
     *
     * @var int|null
     */
    private ?int $teacherCabinet = null;

    /**
     * номер класса
     *
     * @var int|null
     */
    private ?int $classNumber = null;

    /**
     * Буква класса
     *
     * @var string|null
     */
    private ?string $classLetter = null;


    /**
     * Возвращает ид занятий
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Устанавливает ид занятий
     *
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * Возвращает Имя предмета
     *
     * @return string|null
     */
    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    /**
     * Устанавливает Имя предмета
     *
     * @param string|null $itemName
     */
    public function setItemName(?string $itemName): void
    {
        $this->itemName = $itemName;
    }

    /**
     * Возвращает расщифровку предмета
     *
     * @return string|null
     */
    public function getItemDescription(): ?string
    {
        return $this->itemDescription;
    }

    /**
     * Устанавливает расщифровку предмета
     *
     * @param string|null $itemDescription
     */
    public function setItemDescription(?string $itemDescription): void
    {
        $this->itemDescription = $itemDescription;
    }

    /**
     * Возвращает дату проведения занятий
     *
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * Устанавливает дату проведения занятий
     *
     * @param string|null $date
     */
    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    /**
     * Возвращает Фамилию преподователя
     *
     * @return string|null
     */
    public function getTeacherSurname(): ?string
    {
        return $this->teacherSurname;
    }

    /**
     * Устанавливает Фамилию Преподавателю
     *
     * @param string|null $teacherSurname
     */
    public function setTeacherSurname(?string $teacherSurname): void
    {
        $this->teacherSurname = $teacherSurname;
    }

    /**
     * Возвращает Имя Преподавателя
     *
     * @return string|null
     */
    public function getTeacherName(): ?string
    {
        return $this->teacherName;
    }

    /**
     * Устанавливают Имя Преподавателя
     *
     * @param string|null $teacherName
     */
    public function setTeacherName(?string $teacherName): void
    {
        $this->teacherName = $teacherName;
    }

    /**
     * Возвращает Отчество Преподавателя
     *
     * @return string|null
     */
    public function getTeacherPatronymic(): ?string
    {
        return $this->teacherPatronymic;
    }

    /**
     * Устанавливает отчество Преподавателя
     *
     * @param string|null $teacherPatronymic
     */
    public function setTeacherPatronymic(?string $teacherPatronymic): void
    {
        $this->teacherPatronymic = $teacherPatronymic;
    }

    /**
     * Возвращает номер кабинета преподавателя
     *
     * @return int|null
     */
    public function getTeacherCabinet(): ?int
    {
        return $this->teacherCabinet;
    }

    /**
     * Устанавливает номер кабинета преподавателя
     *
     * @param int|null $teacherCabinet
     */
    public function setTeacherCabinet(?int $teacherCabinet): void
    {
        $this->teacherCabinet = $teacherCabinet;
    }

    /**
     * Возвращает номер класса
     *
     * @return int|null
     */
    public function getClassNumber(): ?int
    {
        return $this->classNumber;
    }

    /**
     * Устанавливает номер класса
     *
     * @param int|null $classNumber
     */
    public function setClassNumber(?int $classNumber): void
    {
        $this->classNumber = $classNumber;
    }

    /**
     * Возвращает Букву класса
     *
     * @return string|null
     */
    public function getClassLetter(): ?string
    {
        return $this->classLetter;
    }

    /**
     * Устанавливают Букву Класса
     *
     * @param string|null $classLetter
     */
    public function setClassLetter(?string $classLetter): void
    {
        $this->classLetter = $classLetter;
    }
}
