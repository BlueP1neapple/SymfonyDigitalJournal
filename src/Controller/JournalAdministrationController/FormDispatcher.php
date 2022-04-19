<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller\JournalAdministrationController;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JoJoBizzareCoders\DigitalJournal\Form\CreateAssessmentReportForm;
use JoJoBizzareCoders\DigitalJournal\Form\CreateItemForm;
use JoJoBizzareCoders\DigitalJournal\Form\CreateLessonForm;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService;
use JoJoBizzareCoders\DigitalJournal\Service\NewItemService\NewItemDto;
use JoJoBizzareCoders\DigitalJournal\Service\NewLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\NewReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\NewLessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\NewAssessmentReportDto;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use JoJoBizzareCoders\DigitalJournal\Exception;
use Throwable;


class FormDispatcher
{

    /**
     * @var NewReportService
     */
    private NewReportService $reportService;

    /**
     * @var NewLessonService
     */
    private NewLessonService $lessonService;

    /**
     * @var NewItemService
     */
    private NewItemService $itemService;

    /**
     * @var EntityManager
     */
    private EntityManagerInterface $em;

    /**
     * @param NewReportService $reportService
     * @param NewLessonService $lessonService
     * @param NewItemService $itemService
     * @param EntityManagerInterface $em
     */
    public function __construct(
        NewReportService $reportService,
        NewLessonService $lessonService,
        NewItemService $itemService,
        EntityManagerInterface $em
    ) {
        $this->reportService = $reportService;
        $this->lessonService = $lessonService;
        $this->itemService = $itemService;
        $this->em = $em;
    }


    /**
     * @param Request $request
     * @param callable $formFactory -
     * @return void
     */
    public function dispatcher (Request $request, callable $formFactory): array
    {


        $formLesson           = $this->createForm($formFactory, CreateLessonForm::class);
        $formAssessmentReport = $this->createForm($formFactory, CreateAssessmentReportForm::class);
        $formItem             = $this->createForm($formFactory, CreateItemForm::class);

        $formLesson->handleRequest($request);
        $formAssessmentReport->handleRequest($request);
        $formItem->handleRequest($request);

        if($formAssessmentReport->getData() && $formAssessmentReport->isValid())
        {
            $data = $formAssessmentReport->getData();
            $this->createReport($data);
            $formAssessmentReport = $this->createForm($formFactory,CreateAssessmentReportForm::class);
        } elseif ($formLesson->getData() && $formLesson->isValid())
        {
            $data = $formLesson->getData();
            $this->createLesson($data);
            $formLesson = $this->createForm($formFactory,CreateLessonForm::class);
        } elseif ($formItem->getData() && $formItem->isValid()){
            $data = $formItem->getData();
            $this->createItem($data);
            $formItem = $this->createForm($formFactory,CreateItemForm::class);
        }

        return [
                'form_new_lesson'            =>  $formLesson,
                'form_new_assessment_report' =>  $formAssessmentReport,
                'form_new_item'              =>  $formItem
        ];
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
            $this->reportService->registerAssessmentReport(
                new NewAssessmentReportDto(
                    $dataToCreate['lesson_id']->getId(),
                    $dataToCreate['student_id']->getId(),
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
            $this->lessonService->registerLesson(
                new NewLessonDto(
                    $dataToCreate['item_id']->getId(),
                    $dataToCreate['date']->format('Y.m.d G:i'),
                    $dataToCreate['lesson_duration'],
                    $dataToCreate['teacher_id']->getId(),
                    $dataToCreate['class_id']->getId(),
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
            $this->itemService->registerItem(
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

    private function createForm(callable $formFactory, $formName): FormInterface
    {
        $form = $formFactory($formName);
        if(!$form instanceof FormInterface)
        {
            throw new Exception\RuntimeException('formFactory не является FormInterface');
        }
        return $form;
    }

}