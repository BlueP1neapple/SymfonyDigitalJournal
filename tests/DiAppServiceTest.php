<?php

namespace JoJoBizzareCoders\DigitalJournalTest;

use Exception;
use JoJoBizzareCoders\DigitalJournal\Config\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Config\ContainerExtensions;
use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\FindAssessmentReport;
use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\FindLesson;
use JoJoBizzareCoders\DigitalJournal\ConsoleCommand\HashStr;
use JoJoBizzareCoders\DigitalJournal\Controller\CreateRegisterAssessmentReportController;
use JoJoBizzareCoders\DigitalJournal\Controller\CreateRegisterItemController;
use JoJoBizzareCoders\DigitalJournal\Controller\CreateRegisterLessonController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetAssessmentReportCollectionController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetAssessmentReportController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetLessonCollectionController;
use JoJoBizzareCoders\DigitalJournal\Controller\GetLessonController;
use JoJoBizzareCoders\DigitalJournal\Controller\LoginController;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit\ContainerParams;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\ChainRouters;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\DefaultRouter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RegExpRouter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\RouterInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Router\UniversalRouter;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\DefaultRender;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;
use PHPUnit\Framework\TestCase;

class DiAppServiceTest extends TestCase
{
    /**
     * Провайдер данных для тестирования создаваемых сервисов
     *
     * @return array
     */
    public static function serviceDataProvider(): array
    {
        return [
            HashStr::class => [
                'serviceId' => HashStr::class,
                'expectedServiceClass' => HashStr::class
            ],
            AppConfig::class => [
                'serviceId' => AppConfig::class,
                'expectedServiceClass' => AppConfig::class
            ],
            GetAssessmentReportCollectionController::class => [
                'serviceId' => GetAssessmentReportCollectionController::class,
                'expectedServiceClass' => GetAssessmentReportCollectionController::class
            ],
            GetAssessmentReportController::class => [
                'serviceId' => GetAssessmentReportController::class,
                'expectedServiceClass' => GetAssessmentReportController::class
            ],
            GetLessonCollectionController::class => [
                'serviceId' => GetLessonCollectionController::class,
                'expectedServiceClass' => GetLessonCollectionController::class
            ],
            GetLessonController::class => [
                'serviceId' => GetLessonController::class,
                'expectedServiceClass' => GetLessonController::class
            ],
            CreateRegisterLessonController::class => [
                'serviceId' => CreateRegisterLessonController::class,
                'expectedServiceClass' => CreateRegisterLessonController::class
            ],
            CreateRegisterItemController::class => [
                'serviceId' => CreateRegisterItemController::class,
                'expectedServiceClass' => CreateRegisterItemController::class
            ],
            CreateRegisterAssessmentReportController::class => [
                'serviceId' => CreateRegisterAssessmentReportController::class,
                'expectedServiceClass' => CreateRegisterAssessmentReportController::class
            ],
            LoginController::class => [
                'serviceId' => LoginController::class,
                'expectedServiceClass' => LoginController::class
            ],
            FindLesson::class => [
                'serviceId' => FindLesson::class,
                'expectedServiceClass' => FindLesson::class
            ],
            FindAssessmentReport::class => [
                'serviceId' => FindAssessmentReport::class,
                'expectedServiceClass' => FindAssessmentReport::class
            ],
            RenderInterface::class => [
                'serviceId' => RenderInterface::class,
                'expectedServiceClass' => DefaultRender::class
            ],
            DefaultRouter::class => [
                'serviceId' => DefaultRouter::class,
                'expectedServiceClass' => DefaultRouter::class
            ],
            RegExpRouter::class => [
                'serviceId' => RegExpRouter::class,
                'expectedServiceClass' => RegExpRouter::class
            ],
            UniversalRouter::class => [
                'serviceId' => UniversalRouter::class,
                'expectedServiceClass' => UniversalRouter::class
            ],
            RouterInterface::class => [
                'serviceId' => RouterInterface::class,
                'expectedServiceClass' => ChainRouters::class
            ]
        ];
    }

    /**
     * Проверяет корректность создания сервиса через di контейнер symfony
     *
     * @dataProvider serviceDataProvider
     * @runInSeparateProcess
     * @param string $serviceId - id создаваемого сервиса
     * @param string $expectedServiceClass - проверочный класс которому должен принадлежать созданный сервис
     * @return void
     * @throws Exception
     */
    public function testCreateService(string $serviceId, string $expectedServiceClass): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            new ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );
        $diContainer = $diContainerFactory();
        //Action
        $actualService = $diContainer->get($serviceId);
        //Assert
        $this->assertInstanceOf($expectedServiceClass, $actualService);
    }

}
