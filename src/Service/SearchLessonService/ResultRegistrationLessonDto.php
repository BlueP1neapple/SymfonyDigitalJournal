<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;

class ResultRegistrationLessonDto
{
    /**
     * Айди
     *
     * @var int
     */
    private int $id;


    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


}