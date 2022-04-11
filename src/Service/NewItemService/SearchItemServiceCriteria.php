<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\NewItemService;

class SearchItemServiceCriteria
{
    /**
     * Id предмета
     *
     * @var int|null
     */
    private ?int $id = null;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

}