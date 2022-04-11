<?php

namespace JoJoBizzareCoders\DigitalJournalTest;

use Exception;
use JoJoBizzareCoders\DigitalJournal\Config\AppConfig;
use JoJoBizzareCoders\DigitalJournal\Config\ContainerExtensions;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\ContainerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit\ContainerParams;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception\ExceptionHandler\DefaultExceptionHandler;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\Exception\ExceptionHandler\ExceptionHandlerInterface;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication\App;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\HttpApplication\AppConfiguration;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\NullRender;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\View\RenderInterface;
use JsonException;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;


class AppTest extends TestCase
{
    private static function createDiContainer(): ContainerBuilder
    {
        $containerBuilder = SymfonyDiContainerInit::createContainerBuilder(
            new ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                [
                    'kernel.project_dir' => __DIR__ . '/../'
                ],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $containerBuilder->removeAlias(ExceptionHandlerInterface::class);
        $containerBuilder->setDefinition(DefaultExceptionHandler::class, (new Definition())->setAutowired(true));
        $containerBuilder->setAlias(ExceptionHandlerInterface::class, DefaultExceptionHandler::class)->setPublic(true);

        $containerBuilder->removeAlias(LoggerInterface::class);
        $containerBuilder->setDefinition(NullLogger::class, new Definition());
        $containerBuilder->setAlias(LoggerInterface::class, NullLogger::class)->setPublic(true);
        $containerBuilder->getDefinition(RenderInterface::class)
            ->setClass(NullRender::class)
            ->setArguments([]);
        return $containerBuilder;
    }

    /**
     * Метод используемый в тестах как иллюстрация некорректной работы фабрики
     *
     * @param array $config
     * @return string
     */
    public static function bugFactory(array $config): string
    {
        return 'Oops';
    }

    /**
     * Поставщик данных для тестирования приложения
     *
     * @return \array[][]
     * @throws Exception
     */
    public static function dataProvider(): array
    {
        return [
            'Тестирование возможности смотреть расписание по названию предмета' => [
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            'Тестирование возможности смотреть расписание по расшифровке предмета' => [
                'in' => [
                    'uri' => '/lesson?item_description=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            'Тестирование возможности смотреть расписание по дате' => [
                'in' => [
                    'uri' => '/lesson?lesson_date=2011.11.10 8:30',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            'Тестирование возможности смотреть расписание по Фамилии преподавателя' => [
                'in' => [
                    'uri' => '/lesson?teacher_fio_surname=Круглова',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            'Тестирование возможности смотреть расписание по имени преподавателя' => [
                'in' => [
                    'uri' => '/lesson?teacher_fio_name=Наталия',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            'Тестирование возможности смотреть расписание по отчеству преподавателя' => [
                'in' => [
                    'uri' => '/lesson?teacher_fio_patronymic=Сергеевна',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            'Тестирование возможности смотреть расписание по кабинету преподавателя' => [
                'in' => [
                    'uri' => '/lesson?teacher_cabinet=56',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 2,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 10:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            'Тестирование возможности смотреть расписание по номеру класса' => [
                'in' => [
                    'uri' => '/lesson?class_number=6',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ]
                    ]
                ]
            ],
            'Тестирование возможности смотреть расписание по букве класса' => [
                'in' => [
                    'uri' => '/lesson?class_letter=А',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 3,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 11:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'phone' => '+79222444411',
                                'dateOfBirth' => '1965.01.11',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ],
                        [
                            'id' => 5,
                            'item' => [
                                'id' => 2,
                                'name' => 'ОБЖ',
                                'description' => 'Основы безопасности жизнедеятельности'
                            ],
                            'date' => '2011.11.11 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 2,
                                'fio' => [
                                    'surname' => 'Гусева',
                                    'name' => 'Анна',
                                    'patronymic' => 'Владимировна'
                                ],
                                'phone' => '+79133243412',
                                'dateOfBirth' => '1975.11.01',
                                'address' => [
                                    'street' => 'ул. Зеленская',
                                    'home' => 'д. 22',
                                    'apartment' => 'кв. 11'
                                ],
                                'item' => [
                                    'id' => 2,
                                    'name' => 'ОБЖ',
                                    'description' => 'Основы безопасности жизнедеятельности'
                                ],
                                'cabinet' => 77,
                                'email' => 'guseva@gmail.com'
                            ],
                            'class' => [
                                'id' => 2,
                                'number' => 3,
                                'letter' => 'А'
                            ]
                        ],
                    ]
                ]
            ],
            'Тестирование неподдерживаемого запроса' => [
                'in' => [
                    'uri' => '/hhh?param=ru',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 404,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                ]
            ],
            'Тестирование некорректного ввода названия предмета при поиске занятия' => [
                'in' => [
                    'uri' => '/lesson?item_name[]=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item name'
                    ]
                ]
            ],
            'Тестирование некорректного ввода расшифровки предмета при поиске занятия' => [
                'in' => [
                    'uri' => '/lesson?item_description[]=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item description'
                    ]
                ]
            ],
            'Тестирование некорректного ввода даты занятия при поиске занятия' => [
                'in' => [
                    'uri' => '/lesson?lesson_date[]=2013.11.10 8:30',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect date'
                    ]
                ]
            ],
            'Тестирование некорректного ввода fio преподавателя' => [
                'in' => [
                    'uri' => '/lesson?teacher_fio[]=Круглова Наталия Сергеевна',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect teacher fio'
                    ]
                ]
            ],
            'Тестирование некорректного ввода кабинета преподавателя' => [
                'in' => [
                    'uri' => '/lesson?teacher_cabinet[]=56',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect teacher cabinet'
                    ]
                ]
            ],
            'Тестирование некорректного ввода номера класса' => [
                'in' => [
                    'uri' => '/lesson?class_number[]=6',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect class number'
                    ]
                ]
            ],
            'Тестирование некорректного ввода буквы класса' => [
                'in' => [
                    'uri' => '/lesson?class_letter[]=А',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect class letter'
                    ]
                ]
            ],
            'Тестирование запроса без path' => [
                'in' => [
                    'uri' => '/?param=ru',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 404,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'unsupported request'
                    ]
                ]
            ],
            'Тестирование возможности смотреть оценку по названию предмета' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'dateOfBirth' => '1975.10.01',
                                    'phone' => '+79222444488',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 2,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 10,
                                'fio' => [
                                    'surname' => 'Крабов',
                                    'name' => 'Владимир',
                                    'patronymic' => 'Юрьевич'
                                ],
                                'dateOfBirth' => '2009.04.23',
                                'phone' => '+79888444488',
                                'address' => [
                                    'street' => 'ул. Новая',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 22'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 13,
                                    'fio' => [
                                        'surname' => 'Крабов',
                                        'name' => 'Юрий',
                                        'patronymic' => 'Владимирович'
                                    ],
                                    'phone' => '+79888444488',
                                    'dateOfBirth' => '1985.11.10',
                                    'address' => [
                                        'street' => 'ул. Новая',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 22'
                                    ],
                                    'placeOfWork' => 'ООО Весна',
                                    'email' => 'krabov@gmail.com'
                                ],
                            ],
                            'mark' => 4
                        ],
                        [
                            'id' => 4,
                            'lesson' => [
                                'id' => 2,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                            ],
                            'student' => [
                                'id' => 5,
                                'fio' => [
                                    'surname' => 'Соколова',
                                    'name' => 'Алла',
                                    'patronymic' => 'Юрьевна'
                                ],
                                'dateOfBirth' => '2011.01.12',
                                'phone' => '+79222433488',
                                'address' => [
                                    'street' => 'ул. Зеленская',
                                    'home' => 'д. 47',
                                    'apartment' => 'кв. 34'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 19,
                                    'fio' => [
                                        'surname' => 'Соколова',
                                        'name' => 'Лидия',
                                        'patronymic' => 'Михайловна'
                                    ],
                                    'dateOfBirth' => '1985.01.11',
                                    'phone' => '+79222433488',
                                    'address' => [
                                        'street' => 'ул. Зеленская',
                                        'home' => 'д. 47',
                                        'apartment' => 'кв. 34'
                                    ],
                                    'placeOfWork' => 'ООО Тесты',
                                    'email' => 'sokolova@gmail.com'
                                ],
                            ],
                            'mark' => 4
                        ],
                        [
                            'id' => 8,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 8,
                                'fio' => [
                                    'surname' => 'Кузнецова',
                                    'name' => 'Анастасия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'dateOfBirth' => '2012.11.12',
                                'phone' => '+79223333388',
                                'address' => [
                                    'street' => 'ул. Грузовая',
                                    'home' => 'д. 45',
                                    'apartment' => 'кв. 45'
                                ],
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 17,
                                    'fio' => [
                                        'surname' => 'Кузнецова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Михайловна'
                                    ],
                                    'phone' => '+79223333388',
                                    'dateOfBirth' => '1978.02.05',
                                    'address' => [
                                        'street' => 'ул. Грузовая',
                                        'home' => 'д. 45',
                                        'apartment' => 'кв. 45'
                                    ],
                                    'placeOfWork' => 'ИП Сергеев',
                                    'email' => 'kuznecova@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ]
                    ]
                ]
            ],
            'Тестирование поиска оценок в дневнике по расшифровке названия предмета' => [
                'in' => [
                    'uri' => '/assessmentReport?item_description=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'dateOfBirth' => '1975.10.01',
                                    'phone' => '+79222444488',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 2,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 10,
                                'fio' => [
                                    'surname' => 'Крабов',
                                    'name' => 'Владимир',
                                    'patronymic' => 'Юрьевич'
                                ],
                                'dateOfBirth' => '2009.04.23',
                                'phone' => '+79888444488',
                                'address' => [
                                    'street' => 'ул. Новая',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 22'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 13,
                                    'fio' => [
                                        'surname' => 'Крабов',
                                        'name' => 'Юрий',
                                        'patronymic' => 'Владимирович'
                                    ],
                                    'phone' => '+79888444488',
                                    'dateOfBirth' => '1985.11.10',
                                    'address' => [
                                        'street' => 'ул. Новая',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 22'
                                    ],
                                    'placeOfWork' => 'ООО Весна',
                                    'email' => 'krabov@gmail.com'
                                ],
                            ],
                            'mark' => 4
                        ],
                        [
                            'id' => 4,
                            'lesson' => [
                                'id' => 2,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                            ],
                            'student' => [
                                'id' => 5,
                                'fio' => [
                                    'surname' => 'Соколова',
                                    'name' => 'Алла',
                                    'patronymic' => 'Юрьевна'
                                ],
                                'dateOfBirth' => '2011.01.12',
                                'phone' => '+79222433488',
                                'address' => [
                                    'street' => 'ул. Зеленская',
                                    'home' => 'д. 47',
                                    'apartment' => 'кв. 34'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 19,
                                    'fio' => [
                                        'surname' => 'Соколова',
                                        'name' => 'Лидия',
                                        'patronymic' => 'Михайловна'
                                    ],
                                    'dateOfBirth' => '1985.01.11',
                                    'phone' => '+79222433488',
                                    'address' => [
                                        'street' => 'ул. Зеленская',
                                        'home' => 'д. 47',
                                        'apartment' => 'кв. 34'
                                    ],
                                    'placeOfWork' => 'ООО Тесты',
                                    'email' => 'sokolova@gmail.com'
                                ],
                            ],
                            'mark' => 4
                        ],
                        [
                            'id' => 8,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 8,
                                'fio' => [
                                    'surname' => 'Кузнецова',
                                    'name' => 'Анастасия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'dateOfBirth' => '2012.11.12',
                                'phone' => '+79223333388',
                                'address' => [
                                    'street' => 'ул. Грузовая',
                                    'home' => 'д. 45',
                                    'apartment' => 'кв. 45'
                                ],
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 17,
                                    'fio' => [
                                        'surname' => 'Кузнецова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Михайловна'
                                    ],
                                    'phone' => '+79223333388',
                                    'dateOfBirth' => '1978.02.05',
                                    'address' => [
                                        'street' => 'ул. Грузовая',
                                        'home' => 'д. 45',
                                        'apartment' => 'кв. 45'
                                    ],
                                    'placeOfWork' => 'ИП Сергеев',
                                    'email' => 'kuznecova@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ]
                    ]
                ]
            ],
            'Тестирование поиска оценок в дневнике по дате проведения занятия' => [
                'in' => [
                    'uri' => '/assessmentReport?lesson_date=2011.11.10 8:30',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'phone' => '+79222444411',
                                    'dateOfBirth' => '1965.01.11',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'dateOfBirth' => '1975.10.01',
                                    'phone' => '+79222444488',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 2,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 10,
                                'fio' => [
                                    'surname' => 'Крабов',
                                    'name' => 'Владимир',
                                    'patronymic' => 'Юрьевич'
                                ],
                                'dateOfBirth' => '2009.04.23',
                                'phone' => '+79888444488',
                                'address' => [
                                    'street' => 'ул. Новая',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 22'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 13,
                                    'fio' => [
                                        'surname' => 'Крабов',
                                        'name' => 'Юрий',
                                        'patronymic' => 'Владимирович'
                                    ],
                                    'dateOfBirth' => '1985.11.10',
                                    'phone' => '+79888444488',
                                    'address' => [
                                        'street' => 'ул. Новая',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 22'
                                    ],
                                    'placeOfWork' => 'ООО Весна',
                                    'email' => 'krabov@gmail.com'
                                ],
                            ],
                            'mark' => 4
                        ],
                        [
                            'id' => 8,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 8,
                                'fio' => [
                                    'surname' => 'Кузнецова',
                                    'name' => 'Анастасия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'dateOfBirth' => '2012.11.12',
                                'phone' => '+79223333388',
                                'address' => [
                                    'street' => 'ул. Грузовая',
                                    'home' => 'д. 45',
                                    'apartment' => 'кв. 45'
                                ],
                                'class' => [
                                    'id' => 2,
                                    'number' => 3,
                                    'letter' => 'А'
                                ],
                                'parent' => [
                                    'id' => 17,
                                    'fio' => [
                                        'surname' => 'Кузнецова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Михайловна'
                                    ],
                                    'dateOfBirth' => '1978.02.05',
                                    'phone' => '+79223333388',
                                    'address' => [
                                        'street' => 'ул. Грузовая',
                                        'home' => 'д. 45',
                                        'apartment' => 'кв. 45'
                                    ],
                                    'placeOfWork' => 'ИП Сергеев',
                                    'email' => 'kuznecova@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ]
                    ]
                ]
            ],
            'Тестирование поиска оценок в дневнике по Фамилия cтудента' => [
                'in' => [
                    'uri' => '/assessmentReport?student_fio_surname=Кузнецов',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 3,
                            'lesson' => [
                                'id' => 6,
                                'item' => [
                                    'id' => 3,
                                    'name' => 'Химия',
                                    'description' => 'Химия'
                                ],
                                'date' => '2011.11.11 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 3,
                                    'fio' => [
                                        'surname' => 'Дмитриев',
                                        'name' => 'Дмитрий',
                                        'patronymic' => 'Алексеевна'
                                    ],
                                    'phone' => '+79655346343',
                                    'dateOfBirth' => '1970.02.01',
                                    'address' => [
                                        'street' => 'ул. Круглова',
                                        'home' => 'д. 11',
                                        'apartment' => 'кв. 11'
                                    ],
                                    'item' => [
                                        'id' => 3,
                                        'name' => 'Химия',
                                        'description' => 'Химия'
                                    ],
                                    'cabinet' => 64,
                                    'email' => 'dmitriev@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 3
                        ]
                    ]
                ]
            ],
            'Тестирование поиска оценок в дневнике по Имени cтудента' => [
                'in' => [
                    'uri' => '/assessmentReport?student_fio_name=Алексей',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 3,
                            'lesson' => [
                                'id' => 6,
                                'item' => [
                                    'id' => 3,
                                    'name' => 'Химия',
                                    'description' => 'Химия'
                                ],
                                'date' => '2011.11.11 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 3,
                                    'fio' => [
                                        'surname' => 'Дмитриев',
                                        'name' => 'Дмитрий',
                                        'patronymic' => 'Алексеевна'
                                    ],
                                    'phone' => '+79655346343',
                                    'dateOfBirth' => '1970.02.01',
                                    'address' => [
                                        'street' => 'ул. Круглова',
                                        'home' => 'д. 11',
                                        'apartment' => 'кв. 11'
                                    ],
                                    'item' => [
                                        'id' => 3,
                                        'name' => 'Химия',
                                        'description' => 'Химия'
                                    ],
                                    'cabinet' => 64,
                                    'email' => 'dmitriev@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 3
                        ]
                    ]
                ]
            ],
            'Тестирование поиска оценок в дневнике по отчеству cтудента' => [
                'in' => [
                    'uri' => '/assessmentReport?student_fio_patronymic=Евгеньевич',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 1,
                            'lesson' => [
                                'id' => 1,
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'date' => '2011.11.10 8:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 1,
                                    'fio' => [
                                        'surname' => 'Круглова',
                                        'name' => 'Наталия',
                                        'patronymic' => 'Сергеевна'
                                    ],
                                    'dateOfBirth' => '1965.01.11',
                                    'phone' => '+79222444411',
                                    'address' => [
                                        'street' => 'ул. Ясная',
                                        'home' => 'д. 54',
                                        'apartment' => 'кв. 19'
                                    ],
                                    'item' => [
                                        'id' => 1,
                                        'name' => 'Математика',
                                        'description' => 'Математика'
                                    ],
                                    'cabinet' => 56,
                                    'email' => 'kruglova@gmail.com'
                                ],
                                'class' => [
                                    'id' => 3,
                                    'number' => 6,
                                    'letter' => 'А'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'dateOfBirth' => '2011.01.11',
                                'phone' => '+79222444488',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 5
                        ],
                        [
                            'id' => 3,
                            'lesson' => [
                                'id' => 6,
                                'item' => [
                                    'id' => 3,
                                    'name' => 'Химия',
                                    'description' => 'Химия'
                                ],
                                'date' => '2011.11.11 10:30',
                                'lessonDuration' => 40,
                                'teacher' => [
                                    'id' => 3,
                                    'fio' => [
                                        'surname' => 'Дмитриев',
                                        'name' => 'Дмитрий',
                                        'patronymic' => 'Алексеевна'
                                    ],
                                    'phone' => '+79655346343',
                                    'dateOfBirth' => '1970.02.01',
                                    'address' => [
                                        'street' => 'ул. Круглова',
                                        'home' => 'д. 11',
                                        'apartment' => 'кв. 11'
                                    ],
                                    'item' => [
                                        'id' => 3,
                                        'name' => 'Химия',
                                        'description' => 'Химия'
                                    ],
                                    'cabinet' => 64,
                                    'email' => 'dmitriev@gmail.com'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                            ],
                            'student' => [
                                'id' => 4,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Алексей',
                                    'patronymic' => 'Евгеньевич'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '2011.01.11',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'class' => [
                                    'id' => 1,
                                    'number' => 4,
                                    'letter' => 'Б'
                                ],
                                'parent' => [
                                    'id' => 12,
                                    'fio' => [
                                        'surname' => 'Кузнецов',
                                        'name' => 'Евгений',
                                        'patronymic' => 'Сергеевич'
                                    ],
                                    'phone' => '+79222444488',
                                    'dateOfBirth' => '1975.10.01',
                                    'address' => [
                                        'street' => 'ул. Казанская',
                                        'home' => 'д. 35Б',
                                        'apartment' => 'кв. 23'
                                    ],
                                    'placeOfWork' => 'ООО Алмаз',
                                    'email' => 'kuznecov@gmail.com'
                                ],
                            ],
                            'mark' => 3
                        ]
                    ]
                ]
            ],
            'Тестирование поиска оценок в дневнике по id оценки' => [
                'in' => [
                    'uri' => '/assessmentReport/1',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 200,
                    'result' => [
                        'id' => 1,
                        'lesson' => [
                            'id' => 1,
                            'item' => [
                                'id' => 1,
                                'name' => 'Математика',
                                'description' => 'Математика'
                            ],
                            'date' => '2011.11.10 8:30',
                            'lessonDuration' => 40,
                            'teacher' => [
                                'id' => 1,
                                'fio' => [
                                    'surname' => 'Круглова',
                                    'name' => 'Наталия',
                                    'patronymic' => 'Сергеевна'
                                ],
                                'dateOfBirth' => '1965.01.11',
                                'phone' => '+79222444411',
                                'address' => [
                                    'street' => 'ул. Ясная',
                                    'home' => 'д. 54',
                                    'apartment' => 'кв. 19'
                                ],
                                'item' => [
                                    'id' => 1,
                                    'name' => 'Математика',
                                    'description' => 'Математика'
                                ],
                                'cabinet' => 56,
                                'email' => 'kruglova@gmail.com'
                            ],
                            'class' => [
                                'id' => 3,
                                'number' => 6,
                                'letter' => 'А'
                            ],
                        ],
                        'student' => [
                            'id' => 4,
                            'fio' => [
                                'surname' => 'Кузнецов',
                                'name' => 'Алексей',
                                'patronymic' => 'Евгеньевич'
                            ],
                            'dateOfBirth' => '2011.01.11',
                            'phone' => '+79222444488',
                            'address' => [
                                'street' => 'ул. Казанская',
                                'home' => 'д. 35Б',
                                'apartment' => 'кв. 23'
                            ],
                            'class' => [
                                'id' => 1,
                                'number' => 4,
                                'letter' => 'Б'
                            ],
                            'parent' => [
                                'id' => 12,
                                'fio' => [
                                    'surname' => 'Кузнецов',
                                    'name' => 'Евгений',
                                    'patronymic' => 'Сергеевич'
                                ],
                                'phone' => '+79222444488',
                                'dateOfBirth' => '1975.10.01',
                                'address' => [
                                    'street' => 'ул. Казанская',
                                    'home' => 'д. 35Б',
                                    'apartment' => 'кв. 23'
                                ],
                                'placeOfWork' => 'ООО Алмаз',
                                'email' => 'kuznecov@gmail.com'
                            ],
                        ],
                        'mark' => 5
                    ]
                ]
            ],
            'Тестирование некорректного ввода названия предмета при поиске оценки' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name[]=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item name'
                    ]
                ]
            ],
            'Тестирование некорректного ввода расшифровки предмета при поиске оценки' => [
                'in' => [
                    'uri' => '/assessmentReport?item_description[]=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect item description'
                    ]
                ]
            ],
            'Тестирование некорректного ввода даты занятия при поиске оценки' => [
                'in' => [
                    'uri' => '/assessmentReport?lesson_date[]=2011.11.10 8:30',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect lesson date'
                    ]
                ]
            ],
            'Тестирование некорректного ввода ФИО cтудента' => [
                'in' => [
                    'uri' => '/assessmentReport?student_fio[]=Кузнецов Алексей Евгеньевич',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Incorrect student fio'
                    ]
                ]
            ],
            'Тестирование ситуации когда данные о занятии не корректны. Нет поля date' => [
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToLesson'] = __DIR__ . '/data/broken.lesson.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: date'
                    ]
                ]
            ],
            'Тестирование ситуации когда данные о оценке не корректны. Нет поля mark' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToAssessmentReport'] = __DIR__ . '/data/broken.assessmentReport.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: mark'
                    ]
                ]
            ],
            'Тестирование ситуации когда данные об предметах не корректны. Нет поля description' => [
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToItems'] = __DIR__ . '/data/broken.item.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: description'
                    ]
                ]
            ],
            'Тестирование ситуации когда данные об классах не корректны. Нет поля letter' => [
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToClasses'] = __DIR__ . '/data/broken.class.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Отсутвуют обязательные элементы: letter'
                    ]
                ]
            ],
            'Тестирование ситуации когда данные об родителях не корректны. Нет поля email,login,password' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToParents'] = __DIR__ . '/data/broken.parent.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Данные о фио имеют неверный формат'
                    ]
                ]
            ],
            'Тестирование ситуации когда данные об учениках не корректны. Нет поля address' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToStudents'] = __DIR__ . '/data/broken.student.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Нет данных о аддрессе'
                    ]
                ]
            ],
            'Тестирование ситуации когда данные об учителях не корректны. Нет поля email,login,password' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToTeachers'] = __DIR__ . '/data/broken.teacher.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 503,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Данные о фио имеют неверный формат'
                    ]
                ]
            ],
            'Тестирование ситуации c некорректным путём до файла с занятиями' => [
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToLesson'] = __DIR__ . '/unknown.lesson.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            'Тестирование ситуации c некорректным путём до файла с оценками' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToAssessmentReport'] = __DIR__ . '/unknown.assessmentReport.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            'Тестирование ситуации c некорректным путём до файла с классами' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToClasses'] = __DIR__ . '/unknown.class.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            'Тестирование ситуации c некорректным путём до файла с предметами' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToItems'] = __DIR__ . '/unknown.Item.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            'Тестирование ситуации c некорректным путём до файла с Родителями' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToParents'] = __DIR__ . '/unknown.parent.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            'Тестирование ситуации c некорректным путём до файла с Учениками' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToStudents'] = __DIR__ . '/unknown.student.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            'Тестирование ситуации c некорректным путём до файла с Учителями' => [
                'in' => [
                    'uri' => '/assessmentReport?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $appConfigParams = $c->getParameter('app.configs');
                        $appConfigParams['pathToTeachers'] = __DIR__ . '/unknown.teacher.json';
                        $c->setParameter('app.configs', $appConfigParams);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'Неккоректный путь до файла с данными'
                    ]
                ]
            ],
            'Тестирование ситуации когда нет Конфига' => [
                'in' => [
                    'uri' => '/lesson?item_name=Математика',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->getDefinition(AppConfig::class)->setFactory([AppTest::class, 'bugFactory']);
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [
                    'httpCode' => 500,
                    'result' => [
                        'status' => 'fail',
                        'message' => 'system error'
                    ]
                ]
            ]
        ];
    }


    /**
     * Запуск теста
     *
     * @dataProvider dataProvider
     * @param array $in - входные данные для теста
     * @param array $out - входные данные для проверки
     * @return void
     * @throws JsonException
     */
    public function testApp(array $in, array $out): void
    {
        //Arrange
        $httpRequest = new ServerRequest(
            'GET',
            new Uri($in['uri']),
            ['Content-Type' => 'application/json'],
        );
        $queryParams = [];
        parse_str($httpRequest->getUri()->getQuery(), $queryParams);
        $httpRequest = $httpRequest->withQueryParams($queryParams);
        $diContainer = $in['diContainer'];
        $app = new App(
            (new AppConfiguration())->setContainerFactory(
                static function () use ($diContainer): ContainerInterface {
                    return $diContainer;
                }
            )

        );
        //Action
        $httpResponse = $app->dispatch($httpRequest);
        // Assert
        $this->assertEquals($out['httpCode'], $httpResponse->getStatusCode(), 'Код ответа');
        $this->assertEquals(
            $out['result'],
            json_decode($httpResponse->getBody(), true, 512, JSON_THROW_ON_ERROR),
            'Структура ответа'
        );
    }

}
