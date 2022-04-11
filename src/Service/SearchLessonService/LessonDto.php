<?php

    namespace JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;


    /**
     * Dto объект описывающий структуру информации о занятиях
     */
final class LessonDto
{
    /**
     * id занятий
     *
     * @var int
     */
    private int $id;

    /**
     * Dto объект хранящий информации об предмете
     *
     * @var ItemDto
     */
    private ItemDto $item;

    /**
     * Дата проведения занятия
     *
     * @var string
     */
    private string $date;

    /**
     * Время проведения занятий
     *
     * @var int
     */
    private int $lessonDuration;

    /**
     * Dto объект хранящий информации об преподователях
     *
     * @var TeacherDto
     */
    private TeacherDto $teacher;

    /**
     * Dto объект хранящий информации об классах
     *
     * @var ClassDto
     */
    private ClassDto $class;


    /**
     * Конструктор Dto объект описывающий структуру информации о занятиях
     *
     * @param int $id - id занятий
     * @param ItemDto $item - Dto объект хранящий информации об предмете
     * @param string $date - Дата проведения занятия
     * @param int $lessonDuration - Время проведения занятий
     * @param TeacherDto $teacher - Dto объект хранящий информации об преподователях
     * @param ClassDto $class - Dto объект хранящий информации об классах
     */
    public function __construct(
        int $id,
        ItemDto $item,
        string $date,
        int $lessonDuration,
        TeacherDto $teacher,
        ClassDto $class
    ) {
        $this->id = $id;
        $this->item = $item;
        $this->date = $date;
        $this->lessonDuration = $lessonDuration;
        $this->teacher = $teacher;
        $this->class = $class;
    }

    /**
     * Возвращает id занятия
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает dto объект предмета
     *
     * @return ItemDto
     */
    public function getItem(): ItemDto
    {
        return $this->item;
    }

    /**
     * Возвращает дату проведения занятия
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Возвращает продолжительность проведения занятия
     *
     * @return int
     */
    public function getLessonDuration(): int
    {
        return $this->lessonDuration;
    }

    /**
     * Возвращает dto объект преподователя
     *
     * @return TeacherDto
     */
    public function getTeacher(): TeacherDto
    {
        return $this->teacher;
    }

    /**
     *  Возвращает dto объект класса
     *
     * @return ClassDto
     */
    public function getClass(): ClassDto
    {
        return $this->class;
    }
}
