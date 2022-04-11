<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\ClassClass;
use JoJoBizzareCoders\DigitalJournal\Entity\ClassRepositoryInterface;
use Psr\Log\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\ClassDto;

class SearchClassService
{
    /**
     * Логер
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Репозиторий классов
     *
     * @var ClassRepositoryInterface
     */
    private ClassRepositoryInterface $classRepository;

    /**
     * @param LoggerInterface $logger
     * @param ClassRepositoryInterface $classRepository
     */
    public function __construct(
        LoggerInterface $logger,
        ClassRepositoryInterface $classRepository)
    {
        $this->logger = $logger;
        $this->classRepository = $classRepository;
    }

    public function search():array
    {
        $entitiesCollection = $this->classRepository->findBy([]);
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity){
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->info('found class: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    private function createDto(ClassClass $entity): ClassDto
    {
        return new ClassDto(
            $entity->getId(),
            $entity->getNumber(),
            $entity->getLetter()
        );
    }

}