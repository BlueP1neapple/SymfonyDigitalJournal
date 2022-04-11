<?php

namespace JoJoBizzareCoders\DigitalJournal\Service\SearchItemService;

final class SearchItemServiceCriteria
{

    /**
     * id предметов
     *
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Название предметов
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * Расщифровка названия предметов
     *
     * @var string|null
     */
    private ?string $description = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return SearchItemServiceCriteria
     */
    public function setId(?int $id): SearchItemServiceCriteria
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return SearchItemServiceCriteria
     */
    public function setName(?string $name): SearchItemServiceCriteria
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return SearchItemServiceCriteria
     */
    public function setDescription(?string $description): SearchItemServiceCriteria
    {
        $this->description = $description;
        return $this;
    }


    
}