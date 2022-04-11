<?php

namespace EfTech\BookLibraryTest;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use JoJoBizzareCoders\DigitalJournal\Config\ContainerExtensions;
use JoJoBizzareCoders\DigitalJournal\Entity\ParentUserClass;
use JoJoBizzareCoders\DigitalJournal\Entity\StudentUserClass;
use JoJoBizzareCoders\DigitalJournal\Infrastructure\DI\SymfonyDiContainerInit;
use PHPUnit\Framework\TestCase;

class DoctrineIntegrationTest extends TestCase
{
    public function testCreateEntityManager(): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );
        $diContainer = $diContainerFactory();

        //act
        $em = $diContainer->get(EntityManagerInterface::class);

        //act
        $this->assertInstanceOf(EntityManagerInterface::class, $em);
    }

    /**
     * Проверка что доктрина успешно загружает пользователя
     *
     * @return void
     */
    public function testLoadUser(): void
    {
        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );
        $diContainer = $diContainerFactory();

        /** @var EntityManagerInterface $em */
        $em = $diContainer->get(EntityManagerInterface::class);

        //act

        $user = $em->getRepository(StudentUserClass::class)->findOneBy(['login' => 's0']);

        //Assert
        $this->assertInstanceOf(StudentUserClass::class, $user);
    }

    public function testDoctrineEventSubscriber(): void
    {

        //Arrange
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );
        $diContainer = $diContainerFactory();

        /** @var EntityManagerInterface $em */
        $em = $diContainer->get(EntityManagerInterface::class);

        $eventSubscriber = new class implements EventSubscriber
        {
            /** @var LifecycleEventArgs */
            public $args;
            public function getSubscribedEvents()
            {
                return [Events::postLoad];
            }
            public function postLoad($args): void
            {
                $this->args = $args;
            }
        };
        $em->getEventManager()->addEventSubscriber($eventSubscriber);

        //act
        $user = $em->getRepository(ParentUserClass::class)->findOneBy(['login' => 'r1']);

        //Assert
        $this->assertInstanceOf(LifecycleEventArgs::class, $eventSubscriber->args);
        $this->assertEquals($user, $eventSubscriber->args->getEntity());
    }
}
