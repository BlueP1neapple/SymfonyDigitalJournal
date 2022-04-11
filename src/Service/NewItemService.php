<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Repository\ItemJsonFileRepository;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\NewItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\ResultRegisteringItemDto;

class NewItemService
{

    /**
     * Репозиторий предметов
     *
     * @var ItemRepositoryInterface
     */
    private ItemRepositoryInterface $itemRepository;

    /**
     * @param ItemRepositoryInterface $itemRepository
     */
    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }


    public function registerItem(NewItemDto $itemDto):ResultRegisteringItemDto
    {
        $entity = new ItemClass(
            $this->itemRepository->nexId(),
            $itemDto->getName(),
            $itemDto->getDescription()
        );
        $this->itemRepository->add($entity);
        return new ResultRegisteringItemDto(
            $entity->getId()
        );
    }


}