<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer;

use Ivory\Serializer\Context\Context;
use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Navigator\Navigator;
use Ivory\Serializer\Navigator\NavigatorInterface;
use Ivory\Serializer\Registry\VisitorRegistry;
use Ivory\Serializer\Registry\VisitorRegistryInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Serializer implements SerializerInterface
{
    /**
     * @var NavigatorInterface
     */
    private $navigator;

    /**
     * @var VisitorRegistryInterface
     */
    private $visitorRegistry;

    /**
     * @param NavigatorInterface|null       $navigator
     * @param VisitorRegistryInterface|null $visitorRegistry
     */
    public function __construct(
        NavigatorInterface $navigator = null,
        VisitorRegistryInterface $visitorRegistry = null
    ) {
        $this->navigator = $navigator ?: new Navigator();
        $this->visitorRegistry = $visitorRegistry ?: VisitorRegistry::create();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($data, $format, ContextInterface $context = null)
    {
        return $this->navigate($data, Direction::SERIALIZATION, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data, $type, $format, ContextInterface $context = null)
    {
        return $this->navigate($data, Direction::DESERIALIZATION, $format, $context, $type);
    }

    /**
     * @param mixed                 $data
     * @param int                   $direction
     * @param string                $format
     * @param ContextInterface|null $context
     * @param string|null           $type
     *
     * @return mixed
     */
    private function navigate($data, $direction, $format, ContextInterface $context = null, $type = null)
    {
        $visitor = clone $this->visitorRegistry->getVisitor($direction, $format);

        $context = $context ?: new Context();
        $context
            ->setNavigator($this->navigator)
            ->setVisitor($visitor)
            ->setDirection($direction);

        $this->navigator->navigate($visitor->prepare($data), $type, $context);

        return $visitor->getResult();
    }
}
