<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

use JoJoBizzareCoders\DigitalJournal\Controller\JournalAdministrationController\FormDispatcher;
use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\SearchReportAssessmentCriteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JournalAdministrationController extends AbstractController
{
    /**
     * Сервис оценок
     *
     * @var SearchAssessmentReportService
     */
    private SearchAssessmentReportService $reportService;

    /**
     * Сервис для уроков
     *
     * @var SearchLessonService
     */
    private SearchLessonService $lessonService;

    /**
     * Класс отвечающий за стоздание форм
     *
     * @var FormDispatcher
     */
    private FormDispatcher $formDispatcher;



    /**
     * @param SearchLessonService $lessonService
     * @param SearchAssessmentReportService $reportService
     * @param FormDispatcher $formDispatcher
     */
    public function __construct(
        SearchLessonService $lessonService,
        SearchAssessmentReportService $reportService,
        FormDispatcher $formDispatcher
    ) {
        $this->lessonService = $lessonService;
        $this->reportService = $reportService;
        $this->formDispatcher = $formDispatcher;
    }


    public function __invoke(Request $request): Response
    {
            $formCreateResult = $this->formDispatcher->dispatcher($request, function(string $type){ return $this->createForm($type);});

            $template = 'journal.administration.twig';
            $context =
                [
                    'reports'                    => $this->reportService->search(new SearchReportAssessmentCriteria()),
                    'lessons'                    => $this->lessonService->search(new SearchLessonServiceCriteria()),
                ];
            $context = array_merge($context, $formCreateResult);

            return $this->renderForm($template, $context);
    }
}
