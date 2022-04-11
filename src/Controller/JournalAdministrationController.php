<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use Doctrine\ORM\EntityManagerInterface;
use JoJoBizzareCoders\DigitalJournal\Exception;
use JoJoBizzareCoders\DigitalJournal\Form\CreateAssessmentReportForm;
use JoJoBizzareCoders\DigitalJournal\Form\CreateItemForm;
use JoJoBizzareCoders\DigitalJournal\Form\CreateLessonForm;
use Psr\Log\LoggerInterface;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\NewItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\NewLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\NewReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchClassService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchItemService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\NewLessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\NewAssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\SearchReportAssessmentCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchStudentService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchTeacherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class JournalAdministrationController extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;


    /**
     * Сервис поиска оценок
     *
     * @var SearchAssessmentReportService
     */
    private SearchAssessmentReportService $reportService;

    /**
     * Менеджер сущностей
     *
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * Сервис поиска уроков
     *
     * @var SearchLessonService
     */
    private SearchLessonService $lessonService;

    /**
     * Сервис создания урока
     *
     * @var NewLessonService
     */
    private NewLessonService $newLessonService;

    /**
     * Сервис предметов
     *
     * @var SearchItemService
     */
    private SearchItemService $itemService;

    /**
     * Сервис учетелей
     *
     * @var SearchTeacherService
     */
    private SearchTeacherService $teacherService;

    /**
     * Сервис классов
     *
     * @var SearchClassService
     */
    private SearchClassService $classService;

    /**
     * Сервис по созданию репортов
     *
     * @var NewReportService
     */
    private NewReportService $newReportService;


    /**
     * Поиск студентов
     *
     * @var SearchStudentService
     */
    private SearchStudentService $searchStudentService;

    /**
     * Сервис создания предмета
     *
     * @var NewItemService
     */
    private NewItemService $newItemService;

    private EntityManagerInterface $entityManager;

    /**
     * @param LoggerInterface $logger
     * @param SearchAssessmentReportService $reportService
     * @param SearchLessonService $lessonService
     * @param NewLessonService $newLessonService
     * @param SearchItemService $itemService
     * @param SearchTeacherService $teacherService
     * @param SearchClassService $classService
     * @param NewReportService $newReportService
     * @param SearchStudentService $searchStudentService
     * @param NewItemService $newItemService
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     */
    public function __construct(
        SearchAssessmentReportService $reportService,
        SearchLessonService $lessonService,
        NewLessonService $newLessonService,
        SearchItemService $itemService,
        SearchTeacherService $teacherService,
        SearchClassService $classService,
        NewReportService $newReportService,
        SearchStudentService $searchStudentService,
        NewItemService $newItemService,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        EntityManagerInterface $em
    ) {
        $this->reportService = $reportService;
        $this->lessonService = $lessonService;
        $this->newLessonService = $newLessonService;
        $this->itemService = $itemService;
        $this->teacherService = $teacherService;
        $this->classService = $classService;
        $this->newReportService = $newReportService;
        $this->searchStudentService = $searchStudentService;
        $this->newItemService = $newItemService;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->em = $em;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(Request $request): Response
    {
            $formLesson = $this->createForm(CreateLessonForm::class);
            $formAssessmentReport = $this->createForm(CreateAssessmentReportForm::class);
            $formItem = $this->createForm(CreateItemForm::class);

            $formLesson->handleRequest($request);
            $formAssessmentReport->handleRequest($request);
            $formItem->handleRequest($request);

            if($formAssessmentReport->getData() && $formAssessmentReport->isValid())
            {
                $data = $formAssessmentReport->getData();
                $this->createReport($data);
                $formAssessmentReport = $this->createForm(CreateAssessmentReportForm::class);
            } elseif ($formLesson->getData() && $formLesson->isValid())
            {
                $data = $formLesson->getData();
                $this->createLesson($data);
                $formLesson = $this->createForm(CreateLessonForm::class);
            } elseif ($formItem->getData() && $formItem->isValid()){
                $data = $formItem->getData();
                $this->createItem($data);
                $formItem = $this->createForm(CreateItemForm::class);
            }

            $template = 'journal.administration.twig';
            $context =
                [
                    'form_new_lesson'            => $formLesson,
                    'form_new_assessment_report' => $formAssessmentReport,
                    'form_new_item'              => $formItem,
                    'reports'                    => $this->reportService->search(new SearchReportAssessmentCriteria()),
                    'lessons'                    => $this->lessonService->search(new SearchLessonServiceCriteria()),
                    'items'                      => $this->itemService->search(new SearchItemService\SearchItemServiceCriteria()),
                    'teachers'                   => $this->teacherService->search(),
                    'classes'                    => $this->classService->search(),
                    'students'                   => $this->searchStudentService->search()
                ];

            return $this->renderForm($template, $context);
    }


    /**
     * Функция для создания реперта
     *
     * @param array $dataToCreate
     */
    private function createReport(array $dataToCreate): void
    {
        try {
            $this->em->beginTransaction();
            $this->newReportService->registerAssessmentReport(
                new NewAssessmentReportDto(
                    $dataToCreate['lesson_id'][0]->getId(),
                    $dataToCreate['student_id'][0]->getId(),
                    $dataToCreate['mark']
                )
            );
            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $e) {
            $this->em->rollBack();
            throw new Exception\RuntimeException('Ошибка создания оценки ' . $e);
        }
    }

    /**
     * Функция для создания урока
     *
     * @param array $dataToCreate
     */
    private function createLesson(array $dataToCreate): void
    {
        try {
            $this->em->beginTransaction();
            $this->newLessonService->registerLesson(
                new NewLessonDto(
                    $dataToCreate['item_id'][0]->getId(),
                    $dataToCreate['date']->format('Y.m.d G:i'),
                    $dataToCreate['lesson_duration'],
                    $dataToCreate['teacher_id'][0]->getId(),
                    $dataToCreate['class_id'][0]->getId(),
                )
            );
            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $e) {
            $this->em->rollBack();
            throw new Exception\RuntimeException('Ошибка создания урока ' . $e);
        }
    }

    /**
     * Функция для создания предмета
     *
     * @param array $dataToCreate
     */
    private function createItem(array $dataToCreate): void
    {
        try {
            $this->em->beginTransaction();
            $this->newItemService->registerItem(
                new NewItemDto(
                    $dataToCreate['name'],
                    $dataToCreate['description']
                )
            );
            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $e) {
            $this->em->rollBack();
            throw new Exception\RuntimeException('Ошибка создания предмета ' . $e);
        }
    }

    /**
     * Создаёт дату и время в нужном формате
     *
     * @param array $dataToCreate
     * @return string
     */
    private function createDate(array $dataToCreate): string
    {
        $date = $dataToCreate['date'];
        $time = $dataToCreate['time'];

        return date("Y.m.d", strtotime($date)) . " " . $time;
    }

}
