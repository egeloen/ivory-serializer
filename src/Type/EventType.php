<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Type;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Direction;
use Ivory\Serializer\Event\PostDeserializeEvent;
use Ivory\Serializer\Event\PostSerializeEvent;
use Ivory\Serializer\Event\PreDeserializeEvent;
use Ivory\Serializer\Event\PreSerializeEvent;
use Ivory\Serializer\Event\SerializerEvents;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class EventType implements TypeInterface
{
    /**
     * @var TypeInterface
     */
    private $type;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param TypeInterface            $type
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(TypeInterface $type, EventDispatcherInterface $dispatcher)
    {
        $this->type = $type;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function convert($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $serialization = $context->getDirection() === Direction::SERIALIZATION;

        if ($serialization) {
            $this->dispatcher->dispatch(
                SerializerEvents::PRE_SERIALIZE,
                new PreSerializeEvent($data, $type, $context)
            );
        } else {
            $this->dispatcher->dispatch(
                SerializerEvents::PRE_DESERIALIZE,
                $event = new PreDeserializeEvent($data, $type, $context)
            );

            $data = $event->getData();
        }

        $result = $this->type->convert($data, $type, $context);

        if ($serialization) {
            $this->dispatcher->dispatch(
                SerializerEvents::POST_SERIALIZE,
                new PostSerializeEvent($data, $type, $context)
            );
        } else {
            $this->dispatcher->dispatch(
                SerializerEvents::POST_DESERIALIZE,
                $event = new PostDeserializeEvent($result, $type, $context)
            );

            $result = $event->getData();
        }

        return $result;
    }
}
