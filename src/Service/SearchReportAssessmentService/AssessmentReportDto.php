<?php

    namespace JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService;

    /**
     * Dto объект с информацией о оценках
     */
    final class AssessmentReportDto
    {

        /**
         * id оценки
         *
         * @var int
         */
        private int $id;

        /**
         * Dto объект c информацией об занятии
         *
         * @var LessonDto
         */
        private LessonDto $lesson;

        /**
         * Dto объект с информацией об студентах
         *
         * @var StudentDto
         */
        private StudentDto $student;

        /**
         * Оценка
         *
         * @var int
         */
        private int $mark;



        /**
         * Конструктор dto объекта с информацией о оценках
         *
         * @param int $id - id оценки
         * @param LessonDto $lesson - занятие на которой была поставленна оценка
         * @param StudentDto $student - студент которому была поставлена оценка
         * @param int $mark - оценка
         */
        public function __construct(int $id, LessonDto $lesson, StudentDto $student, int $mark)
        {
            $this->id = $id;
            $this->lesson = $lesson;
            $this->student = $student;
            $this->mark = $mark;
        }

        /**
         * Возвращает id оценки
         *
         * @return int
         */
        public function getId(): int
        {
            return $this->id;
        }

        /**
         * Возвращает занятие на которой была поставленна оценка
         *
         * @return LessonDto
         */
        public function getLesson(): LessonDto
        {
            return $this->lesson;
        }

        /**
         * Возвращает студент которому была поставлена оценка
         *
         * @return StudentDto
         */
        public function getStudent(): StudentDto
        {
            return $this->student;
        }

        /**
         * Возвращает оценка
         *
         * @return int
         */
        public function getMark(): int
        {
            return $this->mark;
        }
    }