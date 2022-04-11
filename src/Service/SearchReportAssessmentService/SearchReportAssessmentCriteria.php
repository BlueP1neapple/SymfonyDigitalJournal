<?php

    namespace JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService;

    /**
     * DTO объект специфицирующий входные данные
     * Критерии поиска оценок
     */
    final class SearchReportAssessmentCriteria
    {
        /**
         * Название предмета
         *
         * @var string|null
         */
        private ?string $itemName = null;

        /**
         * id оценки
         *
         * @var string|null
         */
        private ?string $id = null;

        /**
         * Расшифровка название предмета
         *
         * @var string|null
         */
        private ?string $itemDescription = null;

        /**
         * Дата проведения занятия
         *
         * @var string|null
         */
        private ?string $lessonDate = null;

        /**
         * Имя студента
         *
         * @var string|null
         */
        private ?string $studentSurname = null;

        /**
         * Фамилия студента
         *
         * @var string|null
         */
        private ?string $studentName = null;

        /**
         * Отчество студента
         *
         * @var string|null
         */
        private ?string $studentPatronymic = null;


        /**
         * Возвращает название предмета
         *
         * @return string|null
         */
        public function getItemName(): ?string
        {
            return $this->itemName;
        }

        /**
         * Устанавливает название предмета
         *
         * @param string|null $itemName
         */
        public function setItemName(?string $itemName): void
        {
            $this->itemName = $itemName;
        }

        /**
         * Возвращает id оценки
         *
         * @return string|null
         */
        public function getId(): ?string
        {
            return $this->id;
        }

        /**
         * Устанавливает id оценки
         *
         * @param string|null $id
         */
        public function setId(?string $id): void
        {
            $this->id = $id;
        }

        /**
         * Возвращает расщифровку названия предмета
         *
         * @return string|null
         */
        public function getItemDescription(): ?string
        {
            return $this->itemDescription;
        }

        /**
         * Устанавливает расщифровку названия предмета
         *
         * @param string|null $itemDescription
         */
        public function setItemDescription(?string $itemDescription): void
        {
            $this->itemDescription = $itemDescription;
        }

        /**
         * Возвращает продолжительность занятия
         *
         * @return string|null
         */
        public function getLessonDate(): ?string
        {
            return $this->lessonDate;
        }

        /**
         * Устанавливает продолжительность занятия
         *
         * @param string|null $lessonDate
         */
        public function setLessonDate(?string $lessonDate): void
        {
            $this->lessonDate = $lessonDate;
        }

        /**
         * Возвращает Фамилию предмета
         *
         * @return string|null
         */
        public function getStudentSurname(): ?string
        {
            return $this->studentSurname;
        }

        /**
         * Устанавливает Фамилию предмета
         *
         * @param string|null $studentSurname
         */
        public function setStudentSurname(?string $studentSurname): void
        {
            $this->studentSurname = $studentSurname;
        }

        /**
         * Возвращает имя студента
         *
         * @return string|null
         */
        public function getStudentName(): ?string
        {
            return $this->studentName;
        }

        /**
         * Устанавливает имя студента
         *
         * @param string|null $studentName
         */
        public function setStudentName(?string $studentName): void
        {
            $this->studentName = $studentName;
        }

        /**
         * Возвращает отчество студента
         *
         * @return string|null
         */
        public function getStudentPatronymic(): ?string
        {
            return $this->studentPatronymic;
        }

        /**
         * Устанавливает отчество студента
         *
         * @param string|null $patronymic
         */
        public function setStudentPatronymic(?string $patronymic): void
        {
            $this->studentPatronymic = $patronymic;
        }

    }