<?php

namespace JoJoBizzareCoders\DigitalJournal\ConsoleCommand;

use JoJoBizzareCoders\DigitalJournal\Service\SearchAssessmentReportService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\AssessmentReportDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\ParentDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchReportAssessmentService\SearchReportAssessmentCriteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Поиск оценок в консольном приложении
 */
final class FindAssessmentReport extends Command
{

    /**
     * Сервис поиска оценок
     *
     * @var SearchAssessmentReportService
     */
    private SearchAssessmentReportService $searchAssessmentReportService;

    /**
     * Конструктор Поиска оценок в консольном приложении
     *
     * @param SearchAssessmentReportService $searchAssessmentReportService - сервис поиска оценок
     */
    public function __construct(SearchAssessmentReportService $searchAssessmentReportService)
    {
        parent::__construct(null);
        $this->searchAssessmentReportService = $searchAssessmentReportService;
    }

    protected function configure()
    {
        $this->setName('digitalJournal:find-assessment_report');
        $this->setDescription('Found assessment report');
        $this->setHelp('Find assessment report by criteria');
        $this->addOption('item_name', 'n', InputOption::VALUE_REQUIRED, 'Found by item name');
        $this->addOption('item_description', 'd', InputOption::VALUE_REQUIRED, 'Found by item description');
        $this->addOption('lesson_date', 'l', InputOption::VALUE_REQUIRED, 'Found by lesson date');
        $this->addOption('student_fio_surname', 's', InputOption::VALUE_REQUIRED, 'Found by student fio surname');
        $this->addOption('student_fio_name', 'f', InputOption::VALUE_REQUIRED, 'Found by student fio name');
        $this->addOption('student_fio_patronymic', 'p', InputOption::VALUE_REQUIRED, 'Found by student fio patronymic');
        $this->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'Found by id');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = $input->getOptions();
        $searchAssessmentReportCriteria = new SearchReportAssessmentCriteria();
        $searchAssessmentReportCriteria->setId($params['id'] ?? null);
        $searchAssessmentReportCriteria->setItemName($params['item_name'] ?? null);
        $searchAssessmentReportCriteria->setItemDescription($params['item_description'] ?? null);
        $searchAssessmentReportCriteria->setLessonDate($params['lesson_date'] ?? null);
        $searchAssessmentReportCriteria->setStudentSurname($params['student_fio_surname'] ?? null);
        $searchAssessmentReportCriteria->setStudentName($params['student_fio_name'] ?? null);
        $searchAssessmentReportCriteria->setStudentPatronymic($params['student_fio_patronymic'] ?? null);
        $searchAssessmentReportCriteria->setStudentId($params['student_id'] ?? null);
        $foundReport = $this->searchAssessmentReportService->search($searchAssessmentReportCriteria);
        $jsonData = $this->buildJsonData($foundReport);
        $output->write(json_encode($jsonData, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return self::SUCCESS;
    }

    /**
     * Создание формата json из найденных оценок
     *
     * @param array $assessmentReportCollection - коллекция найденных оценок
     * @return array
     */
    private function buildJsonData(array $assessmentReportCollection): array
    {
        $result = [];
        foreach ($assessmentReportCollection as $assessmentReport) {
            $result[] = $this->serializeAssessmentReport($assessmentReport);
        }
        return $result;
    }

    /**
     * Подготовка данных в формате Json
     *
     * @param AssessmentReportDto $reportDto - экземпляр найденной оценки
     * @return array
     */
    private function serializeAssessmentReport(AssessmentReportDto $reportDto): array
    {
        $jsonData = [
            'id' => $reportDto->getId(),
            'lesson' => [
                'id' => $reportDto->getLesson()->getId(),
                'item' => [
                    'id' => $reportDto->getLesson()->getItem()->getId(),
                    'name' => $reportDto->getLesson()->getItem()->getName(),
                    'description' => $reportDto->getLesson()->getItem()->getDescription()
                ],
                'date' => $reportDto->getLesson()->getDate(),
                'lessonDuration' => $reportDto->getLesson()->getLessonDuration(),
                'teacher' => [
                    'id' => $reportDto->getLesson()->getTeacher()->getId(),
                    'fio' => $reportDto->getLesson()->getTeacher()->getFio(),
                    'dateOfBirth' => $reportDto->getLesson()->getTeacher()->getDateOfBirth(),
                    'phone' => $reportDto->getLesson()->getTeacher()->getPhone(),
                    'address' => $reportDto->getLesson()->getTeacher()->getAddress(),
                    'item' => [
                        'id' => $reportDto->getLesson()->getItem()->getId(),
                        'name' => $reportDto->getLesson()->getItem()->getName(),
                        'description' => $reportDto->getLesson()->getItem()->getDescription()
                    ],
                    'cabinet' => $reportDto->getLesson()->getTeacher()->getCabinet(),
                    'email' => $reportDto->getLesson()->getTeacher()->getEmail()
                ],
                'class' => [
                    'id' => $reportDto->getLesson()->getClass()->getId(),
                    'number' => $reportDto->getLesson()->getClass()->getNumber(),
                    'letter' => $reportDto->getLesson()->getClass()->getLetter()
                ]
            ],
            'student' => [
                'id' => $reportDto->getStudent()->getId(),
                'fio' => $reportDto->getStudent()->getFio(),
                'dateOfBirth' => $reportDto->getStudent()->getDateOfBirth(),
                'phone' => $reportDto->getStudent()->getPhone(),
                'address' => $reportDto->getStudent()->getAddress(),
                'class' => [
                    'id' => $reportDto->getStudent()->getClass()->getId(),
                    'number' => $reportDto->getStudent()->getClass()->getNumber(),
                    'letter' => $reportDto->getStudent()->getClass()->getLetter()
                ]
            ],
            'mark' => $reportDto->getMark()
        ];
        $jsonData['student']['parent'] = array_values(
            array_map(
                static function (ParentDto $dto) {
                    return [
                        'id' => $dto->getId(),
                        'fio' => $dto->getFio(),
                        'dateOfBirth' => $dto->getDateOfBirth(),
                        'phone' => $dto->getPhone(),
                        'address' => $dto->getAddress(),
                        'placeOfWork' => $dto->getPlaceOfWork(),
                        'email' => $dto->getEmail()
                    ];
                },
                $reportDto->getStudent()->getParents()
            )
        );
        return $jsonData;
    }
}
