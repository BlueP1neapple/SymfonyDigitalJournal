<?php

namespace JoJoBizzareCoders\DigitalJournalTest;

use JoJoBizzareCoders\DigitalJournal\Config\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Config\ContainerExtensions;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit\ContainerParams;
use PHPUnit\Framework\TestCase;
use Exception;

class DiAppConfigServiceTest extends TestCase
{
    /**
     * Поставщик данных для Тестирование получение значений из конфига приложений
     *
     * @return array[]
     */
    public static function appConfigDataProvider(): array
    {
        return [
            'pathToLesson' => [
                'method' => 'getPathToLesson',
                'expectedValue' => __DIR__ . '/../data/lesson.json',
                'isPath' => true
            ],
            'pathToAssessmentReport' => [
                'method' => 'getPathToAssessmentReport',
                'expectedValue' => __DIR__ . '/../data/assessmentReport.json',
                'isPath' => true
            ],
            'pathToItems' => [
                'method' => 'getPathToItems',
                'expectedValue' => __DIR__ . '/../data/item.json',
                'isPath' => true
            ],
            'pathToTeachers' => [
                'method' => 'getPathToTeachers',
                'expectedValue' => __DIR__ . '/../data/teacher.json',
                'isPath' => true
            ],
            'pathToClasses' => [
                'method' => 'getPathToClasses',
                'expectedValue' => __DIR__ . '/../data/class.json',
                'isPath' => true
            ],
            'pathToStudents' => [
                'method' => 'getPathToStudents',
                'expectedValue' => __DIR__ . '/../data/student.json',
                'isPath' => true
            ],
            'pathToParents' => [
                'method' => 'getPathToParents',
                'expectedValue' => __DIR__ . '/../data/parent.json',
                'isPath' => true
            ],
            'pathToLogFile' => [
                'method' => 'getPathToLogFile',
                'expectedValue' => __DIR__ . '/../var/log/app.log',
                'isPath' => true
            ],
            'hideErrorMessage' => [
                'method' => 'isHideErrorMessage',
                'expectedValue' => false,
                'isPath' => false
            ],
            'loginUri' => [
                'method' => 'getLoginUri',
                'expectedValue' => '/login',
                'isPath' => false
            ],
        ];
    }

    /**
     * Тестирование получение значений из конфига приложений
     *
     * @dataProvider appConfigDataProvider
     * @param string $method
     * @param $expectedValue
     * @param bool $isPath
     * @return void
     * @throws Exception
     */
    public function testAppConfigGetter(string $method, $expectedValue, bool $isPath): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            new ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            ),
        );
        $diContainer = $diContainerFactory();
        $appConfig = $diContainer->get(AppConfig::class);
        //Action
        $actualValue = $appConfig->$method();
        //Assert
        if ($isPath) {
            $expectedValue = realpath($expectedValue);
            $actualValue = realpath($actualValue);
        }
        $this->assertSame($expectedValue, $actualValue);
    }

}
