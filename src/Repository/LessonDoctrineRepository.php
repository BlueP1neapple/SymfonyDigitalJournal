<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonClass;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Exception;

class LessonDoctrineRepository extends EntityRepository implements
    LessonRepositoryInterface
{
    private const REPLACED_TEACHER_PROPERTY = [
        'fio_surname' => 'fio.surname',
        'fio_name' => 'fio.name',
        'fio_patronymic' => 'fio.patronymic'
    ];
    /**
     * @inheritDoc
     */
    public function nextId(): int
    {
        return $this->getClassMetadata()->idGenerator->generateId($this->getEntityManager(), null);
    }

    /**
     * @inheritDoc
     */
    public function add(LessonClass $entity): LessonClass
    {
        $this->getEntityManager()->persist($entity);
        return $entity;
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select(['l'])
            ->from(LessonClass::class, 'l')
            ->leftJoin('l.item', 'i')
            ->leftJoin('l.teacher', 't')
            ->leftJoin('t.item', 'ti')
            ->leftJoin('l.class', 'c');
        $this->buildWhere($queryBuilder, $criteria);

        return $queryBuilder->getQuery()->getResult();
    }

    private function buildWhere(QueryBuilder $queryBuilder, array $criteria)
    {
        if (count($criteria) === 0) {
            return;
        }

        if(key_exists('id', $criteria)){
            $criteria['lesson_id'] = $criteria['id'];
            unset($criteria['id']);
        }

        $whereExprAnd = $queryBuilder->expr()->andX();

        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (strpos($criteriaName, 'item_') === 0) {
                $preparedCriteriaName = substr($criteriaName, 5);
                $whereExprAnd->add($queryBuilder->expr()->eq("i.$preparedCriteriaName", ":$criteriaName"));
            } elseif (strpos($criteriaName, 'lesson_') === 0) {
                $preparedCriteriaName = substr($criteriaName, 7);
                $whereExprAnd->add($queryBuilder->expr()->eq("l.$preparedCriteriaName", ":$criteriaName"));
            } elseif (strpos($criteriaName, 'teacher_') === 0) {
                $preparedCriteriaName = $this->prepareTeacherCriteria($criteriaName);
                $whereExprAnd->add($queryBuilder->expr()->eq("t.$preparedCriteriaName", ":$criteriaName"));
            } elseif (strpos($criteriaName, 'class_') === 0) {
                $preparedCriteriaName = substr($criteriaName, 6);
                $whereExprAnd->add($queryBuilder->expr()->eq("c.$preparedCriteriaName", ":$criteriaName"));
            }
        }
        $queryBuilder->where($whereExprAnd);
        $queryBuilder->setParameters($criteria);
    }

    private function prepareTeacherCriteria(string $key): string
    {
        $propertyName = substr($key, 8);
        if (array_key_exists($propertyName, self::REPLACED_TEACHER_PROPERTY)) {
            $preparedCriteriaName = self::REPLACED_TEACHER_PROPERTY[$propertyName];
        } else {
            $preparedCriteriaName = $propertyName;
        }
        return $preparedCriteriaName;
    }
}
