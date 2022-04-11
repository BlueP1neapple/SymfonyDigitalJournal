<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService;

final class NewAssessmentReportDto
{
    /**
     * Id занятия
     *
     * @var int
     */
    private int $lessonId;

    /**
     * Id ученика
     *
     * @var int
     */
    private int $studentId;

    /**
     * оценка
     *
     * @var int
     */
    private int $mark;

    /**
     * @param int $lessonId - Id занятия
     * @param int $studentId - Id ученика
     * @param int $mark - Значение оценки
     */
    public function __construct(int $lessonId, int $studentId, int $mark)
    {
        $this->lessonId = $lessonId;
        $this->studentId = $studentId;
        $this->mark = $mark;
    }

    /**
     * Возвращает Id занятия
     *
     * @return int
     */
    public function getLessonId(): int
    {
        return $this->lessonId;
    }

    /**
     * Возвращает Id ученика
     *
     * @return int
     */
    public function getStudentId(): int
    {
        return $this->studentId;
    }

    /**
     * Возвращает Значение оценки
     *
     * @return int
     */
    public function getMark(): int
    {
        return $this->mark;
    }

}