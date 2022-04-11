<?php

namespace JoJoBizzareCoders\DigitalJournal\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use JoJoBizzareCoders\DigitalJournal\Entity\AssessmentReportRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ReportClass;

class AssessmentReportDoctrineRepository extends EntityRepository implements
    AssessmentReportRepositoryInterface
{
    /**
     * Критерии для замены
     */
    private const REPLACED_STUDENT_PROPERTY = [
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
    public function save(ReportClass $entity): ReportClass
    {
        $this->getEntityManager()->persist($entity);
        return $entity;
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select(['r', 'l', 's', 't', 'p', 'sc'])
            ->from(ReportClass::class, 'r')
            ->leftJoin('r.lesson', 'l')
            ->leftJoin('r.student', 's')
            ->leftJoin('l.class', 'lc')
            ->leftJoin('l.item', 'li')
            ->leftJoin('l.teacher', 't')
            ->leftJoin('s.parents', 'p')
            ->leftJoin('s.class', 'sc')
            ->leftJoin('t.item', 'ti')->orderBy('r.id');
        $this->buildWhere($queryBuilder, $criteria);



        return $queryBuilder->getQuery()->getResult();
    }


    private function buildWhere(QueryBuilder $queryBuilder, array $criteria)
    {
        if (count($criteria) === 0) {
            return;
        }

        $whereExprAnd = $queryBuilder->expr()->andX();

        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (strpos($criteriaName, 'item_') === 0) {
                $preparedCriteriaName = substr($criteriaName, 5);
                $whereExprAnd->add($queryBuilder->expr()->eq("li.$preparedCriteriaName", ":$criteriaName"));
            } elseif (strpos($criteriaName, 'lesson_') === 0) {
                $preparedCriteriaName = substr($criteriaName, 7);
                $whereExprAnd->add($queryBuilder->expr()->eq("l.$preparedCriteriaName", ":$criteriaName"));
            } elseif (strpos($criteriaName, 'student_') === 0) {
                $preparedCriteriaName = $this->prepareStudentCriteria($criteriaName);
                $whereExprAnd->add($queryBuilder->expr()->eq("s.$preparedCriteriaName", ":$criteriaName"));
            } elseif (strpos($criteriaName, 'report_') === 0) {
                $preparedCriteriaName = substr($criteriaName, 7);
                $whereExprAnd->add($queryBuilder->expr()->eq("r.$preparedCriteriaName", ":$criteriaName"));
            }
        }


        $queryBuilder->where($whereExprAnd);
        $queryBuilder->setParameters($criteria);
    }

    private function prepareStudentCriteria(string $key): string
    {
        $propertyName = substr($key, 8);
        if (array_key_exists($propertyName, self::REPLACED_STUDENT_PROPERTY)) {
            $preparedCriteriaName = self::REPLACED_STUDENT_PROPERTY[$propertyName];
        } else {
            $preparedCriteriaName = $propertyName;
        }
        return $preparedCriteriaName;
    }
}
