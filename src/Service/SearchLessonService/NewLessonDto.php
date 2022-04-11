<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;

final class NewLessonDto
{
    /**
     * Айди предмета
     *
     * @var int
     */
    private int $itemId;

    /**
     * Дата проведения урока
     *
     * @var string
     */
    private string $date;

    /**
     * Длительность урока
     *
     * @var int
     */
    private int $lessonDuration;

    /**
     * Айди преподавателя
     * @var int
     */
    private int $teacherId;

    /**
     * Айди класса
     *
     * @var int
     */
    private int $classId;

    /**
     * @param int $itemId Айди предмета
     * @param string $date Дата проведения урока
     * @param int $lessonDuration Длительность урока
     * @param int $teacherId Айди преподавателя
     * @param int $classId Айди класса
     */
    public function __construct(int $itemId, string $date, int $lessonDuration, int $teacherId, int $classId)
    {
        $this->itemId = $itemId;
        $this->date = $date;
        $this->lessonDuration = $lessonDuration;
        $this->teacherId = $teacherId;
        $this->classId = $classId;
    }

    /**
     * @return int
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getLessonDuration(): int
    {
        return $this->lessonDuration;
    }

    /**
     * @return int
     */
    public function getTeacherId(): int
    {
        return $this->teacherId;
    }

    /**
     * @return int
     */
    public function getClassId(): int
    {
        return $this->classId;
    }




}