<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\ItemClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ItemRepositoryInterface;
use Psr\Log\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService\ItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService\SearchItemServiceCriteria;

/**
 * Сервис поиска предметов
 */
final class SearchItemService
{
    /**
     * Используемый логер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Репозиторий для работы с предметами
     *
     * @var ItemRepositoryInterface
     */
    private ItemRepositoryInterface $itemRepository;


    /**
     * @param LoggerInterface $logger - Используемый логгер
     * @param ItemRepositoryInterface $itemRepository - Репозиторий для работы с предметов
     */
    public function __construct(LoggerInterface $logger, ItemRepositoryInterface $itemRepository)
    {
        $this->logger = $logger;
        $this->itemRepository = $itemRepository;
    }

    /**
     * Метод осуществления поиска предметов по критериям
     *
     * @param SearchItemServiceCriteria $searchItemServiceCriteria  - критерии поиска занятий
     * @return array
     */
    public function search(SearchItemServiceCriteria $searchItemServiceCriteria): array
    {
        $criteria = $this->searchCriteriaForArray($searchItemServiceCriteria);
        $entitiesCollection = $this->itemRepository->findBy($criteria);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->debug('found items: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    /**
     * Преобразует критерии поиска в массив
     *
     * @param SearchItemServiceCriteria $searchItemServiceCriteria
     * @return array
     */
    private function searchCriteriaForArray(SearchItemServiceCriteria $searchItemServiceCriteria): array
    {
        $criteriaForRepository = [
            'id' => $searchItemServiceCriteria->getId()
        ];
        return array_filter($criteriaForRepository, static function ($v): bool {
            return null !== $v;
        });
    }

    /**
     * Создание dto объекта с информацией об предметов
     *
     * @param ItemClass $item
     * @return ItemDto
     */
    private function createDto(ItemClass $item): ItemDto
    {
        return new ItemDto(
            $item->getId(),
            $item->getName(),
            $item->getDescription()
        );
    }
}
