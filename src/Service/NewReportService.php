<?php

namespace JoJoBizzareCoders\DigitalJournal\Service;

use JoJoBizzareCoders\DigitalJournal\Entity\AssessmentReportRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\LessonRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Entity\ReportClass;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentRepositoryInterface;
use JoJoBizzareCoders\DigitalJournal\Exception\RuntimeException;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\NewAssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ResultRegisteringAssessmentReportDto;
use JsonException;

class NewReportService
{
    /**
     * Репризиторий для работы с оценками
     *
     * @var AssessmentReportRepositoryInterface
     */
    private AssessmentReportRepositoryInterface $assessmentReportRepository;

    /**
     * Репризиторий для работы с занятиями
     *
     * @var LessonRepositoryInterface
     */
    private LessonRepositoryInterface $lessonRepository;

    /**
     * Репризиторий для работы с студентами
     *
     * @var StudentRepositoryInterface
     */
    private StudentRepositoryInterface $studentRepository;


    /**
     * @param AssessmentReportRepositoryInterface $assessmentReportRepository - Репризиторий для работы с оценками
     * @param LessonRepositoryInterface $lessonRepository - Репризиторий для работы с занятиями
     * @param StudentRepositoryInterface $studentRepository - Репризиторий для работы с студентами
     */
    public function __construct(
        AssessmentReportRepositoryInterface $assessmentReportRepository,
        LessonRepositoryInterface $lessonRepository,
        StudentRepositoryInterface $studentRepository
    ) {
        $this->assessmentReportRepository = $assessmentReportRepository;
        $this->lessonRepository = $lessonRepository;
        $this->studentRepository = $studentRepository;
    }

    /**
     * Создание новой оценки
     *
     * @param NewAssessmentReportDto $assessmentReportDto - dto с информацией о новой оценки
     * @return ResultRegisteringAssessmentReportDto
     * @throws JsonException
     */
    public function registerAssessmentReport(NewAssessmentReportDto $assessmentReportDto):
    ResultRegisteringAssessmentReportDto
    {
        $lessonId = $assessmentReportDto->getLessonId();
        $studentId = $assessmentReportDto->getStudentId();
        $lessonsCollection = $this->lessonRepository->findBy(['id' => $lessonId]);
        $studentsCollection = $this->studentRepository->findBy(['id' => $studentId]);
        if (1 !== count($lessonsCollection)) {
            throw new RuntimeException(
                "Нельзя зарегестрировать оценку с lesson_id='$lessonId'. Занятие с данным id не найден"
            );
        }
        if (1 !== count($studentsCollection)) {
            throw new RuntimeException(
                "Нельзя зарегестрировать оценку с student_id='$studentId'. Студент с данным id не найден"
            );
        }
        $lesson = current($lessonsCollection);
        $student = current($studentsCollection);
        $entity = new ReportClass(
            $this->assessmentReportRepository->nextId(),
            $lesson,
            $student,
            $assessmentReportDto->getMark()
        );
        $this->assessmentReportRepository->save($entity);
        return new ResultRegisteringAssessmentReportDto(
            $entity->getId()
        );
    }
}
