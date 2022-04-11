<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService;

class ResultRegisteringAssessmentReportDto
{
    /**
     * id оценки
     *
     * @var int
     */
    private int $id;

    /**
     * @param int $id - id оценки
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Возвпращает id оценки
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}