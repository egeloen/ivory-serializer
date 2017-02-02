<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Event;

use Ivory\Serializer\Context\Context;
use Ivory\Serializer\Event\PostDeserializeEvent;
use Ivory\Serializer\Event\PostSerializeEvent;
use Ivory\Serializer\Event\PreDeserializeEvent;
use Ivory\Serializer\Event\PreSerializeEvent;
use Ivory\Serializer\Event\SerializerEvents;
use Ivory\Serializer\Format;
use Ivory\Serializer\Mapping\TypeMetadata;
use Ivory\Serializer\Navigator\EventNavigator;
use Ivory\Serializer\Navigator\Navigator;
use Ivory\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class EventSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->dispatcher = $this->createEventDispatcherMock();
        $this->serializer = new Serializer(new EventNavigator(new Navigator(), $this->dispatcher));
    }

    public function testPreSerializeEvent()
    {
        $data = 'data';
        $context = new Context();

        $this->dispatcher
            ->expects($this->at(0))
            ->method('dispatch')
            ->with(
                $this->identicalTo(SerializerEvents::PRE_SERIALIZE),
                $this->callback(function ($event) use ($data, $context) {
                    if (!$event instanceof PreSerializeEvent) {
                        return false;
                    }

                    if ($event->getData() !== $data) {
                        return false;
                    }

                    if ($event->getContext() !== $context) {
                        return false;
                    }

                    $event->setData(123);
                    $event->setType(new TypeMetadata('integer'));

                    return true;
                })
            );

        $this->assertSame('123', $this->serializer->serialize($data, Format::JSON, $context));
    }

    public function testPostSerializeEvent()
    {
        $data = 'data';
        $context = new Context();

        $this->dispatcher
            ->expects($this->at(1))
            ->method('dispatch')
            ->with(
                $this->identicalTo(SerializerEvents::POST_SERIALIZE),
                $this->callback(function ($event) use ($data, $context) {
                    return $event instanceof PostSerializeEvent
                        && $event->getData() === $data
                        && $event->getContext() === $context;
                })
            );

        $this->assertSame('"data"', $this->serializer->serialize($data, Format::JSON, $context));
    }

    public function testPreDeserializeEvent()
    {
        $data = '123';
        $context = new Context();

        $this->dispatcher
            ->expects($this->at(0))
            ->method('dispatch')
            ->with(
                $this->identicalTo(SerializerEvents::PRE_DESERIALIZE),
                $this->callback(function ($event) use ($data, $context) {
                    if (!$event instanceof PreDeserializeEvent) {
                        return false;
                    }

                    if ($event->getData() !== (int) $data) {
                        return false;
                    }

                    if ($event->getContext() !== $context) {
                        return false;
                    }

                    $event->setData('data');
                    $event->setType(new TypeMetadata('string'));

                    return true;
                })
            );

        $this->assertSame('data', $this->serializer->deserialize($data, 'integer', Format::JSON, $context));
    }

    public function testPostDeserializeEvent()
    {
        $data = 123;
        $context = new Context();

        $this->dispatcher
            ->expects($this->at(1))
            ->method('dispatch')
            ->with(
                $this->identicalTo(SerializerEvents::POST_DESERIALIZE),
                $this->callback(function ($event) use ($data, $context) {
                    return $event instanceof PostDeserializeEvent
                        && $event->getData() === $data
                        && $event->getContext() === $context;
                })
            );

        $this->assertSame($data, $this->serializer->deserialize('123', 'integer', Format::JSON, $context));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EventDispatcherInterface
     */
    private function createEventDispatcherMock()
    {
        return $this->createMock(EventDispatcherInterface::class);
    }
}
