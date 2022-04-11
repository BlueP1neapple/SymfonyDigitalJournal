<?php


namespace JoJoBizzareCoders\DigitalJournal\ConsoleCommand;

use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\LessonDto;
use JoJoBizzareCoders\DigitalJournal\Service\SearchLessonService\SearchLessonServiceCriteria;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Поиск занятий в консольном приложении
 */
final class FindLesson extends Command
{
    /**
     * Сервис поиска занятий
     *
     * @var SearchLessonService
     */
    private SearchLessonService $searchLessonService;

    public function __construct(SearchLessonService $searchLessonService)
    {
        parent::__construct(null);
        $this->searchLessonService = $searchLessonService;
    }


    protected function configure()
    {
        $this->setName('digitalJournal:find-lesson');
        $this->setDescription('Found lesson');
        $this->setHelp('Find lesson by criteria');
        $this->addOption('class_number', 'n', InputOption::VALUE_REQUIRED, 'Found by class number');
        $this->addOption('class_letter', 'l', InputOption::VALUE_REQUIRED, 'Found by class letter');
        $this->addOption('teacher_cabinet', 'c', InputOption::VALUE_REQUIRED, 'Found by teacher cabinet');
        $this->addOption('teacher_fio_surname', 's', InputOption::VALUE_REQUIRED, 'Found by teacher fio surname');
        $this->addOption('teacher_fio_name', 'f', InputOption::VALUE_REQUIRED, 'Found by teacher fio name');
        $this->addOption('teacher_fio_patronymic', 'p', InputOption::VALUE_REQUIRED, 'Found by teacher fio patronymic');
        $this->addOption('lesson_date', 'd', InputOption::VALUE_REQUIRED, 'Found by lesson date');
        $this->addOption('item_name', 'a', InputOption::VALUE_REQUIRED, 'Found by item name');
        $this->addOption('item_description', 'a', InputOption::VALUE_REQUIRED, 'Found by item description');
        $this->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'Found by id');
        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = $input->getOptions();
        $searchLessonServiceCriteria = new SearchLessonServiceCriteria();
        $searchLessonServiceCriteria->setId($params['id'] ?? null);
        $searchLessonServiceCriteria->setTeacherSurname($params['teacher_fio_surname'] ?? null);
        $searchLessonServiceCriteria->setTeacherName($params['teacher_fio_name'] ?? null);
        $searchLessonServiceCriteria->setTeacherPatronymic($params['teacher_fio_patronymic'] ?? null);
        $searchLessonServiceCriteria->setClassNumber($params['class_number'] ?? null);
        $searchLessonServiceCriteria->setClassLetter($params['class_letter'] ?? null);
        $searchLessonServiceCriteria->setTeacherCabinet($params['teacher_cabinet'] ?? null);
        $searchLessonServiceCriteria->setDate($params['lesson_date'] ?? null);
        $searchLessonServiceCriteria->setItemName($params['item_name'] ?? null);
        $searchLessonServiceCriteria->setItemDescription($params['item_description'] ?? null);
        $lessonsDto = $this->searchLessonService->search($searchLessonServiceCriteria);
        $jsonData = $this->buildJsonData($lessonsDto);
        $output->write(json_encode($jsonData, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return self::SUCCESS;
    }

    /**
     * Создаём формат Json из найденных занятий
     *
     * @param array $foundLessons - найденные занятия
     * @return array
     */
    private function buildJsonData(array $foundLessons): array
    {
        $result = [];
        foreach ($foundLessons as $foundLesson) {
            $result[] = $this->serializeLesson($foundLesson);
        }
        return $result;
    }

    /**
     * Подготовка формата json с информацией о найденном занятии
     *
     * @param LessonDto $foundLesson - найденное занятие
     * @return array
     */
    private function serializeLesson(LessonDto $foundLesson): array
    {
        $jsonData = [
            'id' => $foundLesson->getId(),
            'date' => $foundLesson->getDate(),
            'lessonDuration' => $foundLesson->getLessonDuration(),
        ];
        $itemDto = $foundLesson->getItem();
        $jsonData['item'] = [
            'id' => $itemDto->getId(),
            'name' => $itemDto->getName(),
            'description' => $itemDto->getDescription()
        ];
        $teacherDto = $foundLesson->getTeacher();
        $jsonData['teacher'] = [
            'id' => $teacherDto->getId(),
            'fio' => $teacherDto->getFio(),
            'dateOfBirth' => $teacherDto->getDateOfBirth(),
            'phone' => $teacherDto->getPhone(),
            'address' => $teacherDto->getAddress(),
            'item' => $jsonData['item'],
            'cabinet' => $teacherDto->getCabinet(),
            'email' => $teacherDto->getEmail()
        ];
        $classDto = $foundLesson->getClass();
        $jsonData['class'] = [
            'id' => $classDto->getId(),
            'number' => $classDto->getNumber(),
            'letter' => $classDto->getLetter()
        ];
        return $jsonData;
    }
}
