<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Mapping\Factory;

use Ivory\Serializer\Event\ClassMetadataLoadEvent;
use Ivory\Serializer\Event\ClassMetadataNotFoundEvent;
use Ivory\Serializer\Event\SerializerEvents;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Ivory\Serializer\Mapping\Factory\EventClassMetadataFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class EventClassMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventClassMetadataFactory
     */
    private $eventFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ClassMetadataFactoryInterface
     */
    private $factory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * {@inheritdoc}
     */
    protected function setup()
    {
        $this->dispatcher = $this->createEventDispatcherMock();
        $this->factory = $this->createClassMetadataFactoryMock();

        $this->eventFactory = new EventClassMetadataFactory($this->factory, $this->dispatcher);
    }

    public function testClassMetadataLoadEvent()
    {
        $this->factory
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with($this->identicalTo($class = \stdClass::class))
            ->will($this->returnValue($classMetadata = $this->createClassMetadataMock()));

        $this->dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->identicalTo(SerializerEvents::CLASS_METADATA_LOAD),
                $this->callback(function ($event) use ($classMetadata) {
                    return $event instanceof ClassMetadataLoadEvent && $event->getClassMetadata() === $classMetadata;
                })
            );

        $this->assertSame($classMetadata, $this->eventFactory->getClassMetadata($class));
    }

    public function testClassMetadataNotFoundEvent()
    {
        $classMetadata = $this->createClassMetadataMock();

        $this->factory
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with($this->identicalTo($class = \stdClass::class))
            ->will($this->returnValue(null));

        $this->dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->identicalTo(SerializerEvents::CLASS_METADATA_NOT_FOUND),
                $this->callback(function ($event) use ($class, $classMetadata) {
                    if (!$event instanceof ClassMetadataNotFoundEvent) {
                        return false;
                    }

                    if ($event->getClass() !== $class) {
                        return false;
                    }

                    $event->setClassMetadata($classMetadata);

                    return true;
                })
            );

        $this->assertSame($classMetadata, $this->eventFactory->getClassMetadata($class));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClassMetadataFactoryInterface
     */
    private function createClassMetadataFactoryMock()
    {
        return $this->createMock(ClassMetadataFactoryInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EventDispatcherInterface
     */
    private function createEventDispatcherMock()
    {
        return $this->createMock(EventDispatcherInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClassMetadataInterface
     */
    private function createClassMetadataMock()
    {
        return $this->createMock(ClassMetadataInterface::class);
    }
}
