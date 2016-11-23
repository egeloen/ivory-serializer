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
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Navigator\Navigator;
use Ivory\Serializer\Navigator\NavigatorInterface;
use Ivory\Serializer\Registry\VisitorRegistry;
use Ivory\Serializer\Registry\VisitorRegistryInterface;
use Ivory\Serializer\Type\Parser\TypeParser;
use Ivory\Serializer\Type\Parser\TypeParserInterface;

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
     * @var TypeParserInterface
     */
    private $typeParser;

    /**
     * @param NavigatorInterface|null       $navigator
     * @param VisitorRegistryInterface|null $visitorRegistry
     * @param TypeParserInterface|null      $typeParser
     */
    public function __construct(
        NavigatorInterface $navigator = null,
        VisitorRegistryInterface $visitorRegistry = null,
        TypeParserInterface $typeParser = null
    ) {
        $this->navigator = $navigator ?: new Navigator();
        $this->visitorRegistry = $visitorRegistry ?: VisitorRegistry::create();
        $this->typeParser = $typeParser ?: new TypeParser();
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
        if (is_string($type)) {
            $type = $this->typeParser->parse($type);
        }

        if (!$type instanceof TypeMetadataInterface) {
            throw new \InvalidArgumentException(sprintf(
                'The type must be a string or a "%s", got "%s".',
                TypeMetadataInterface::class,
                is_object($type) ? get_class($type) : gettype($type)
            ));
        }

        return $this->navigate($data, Direction::DESERIALIZATION, $format, $context, $type);
    }

    /**
     * @param mixed                      $data
     * @param int                        $direction
     * @param string                     $format
     * @param ContextInterface|null      $context
     * @param TypeMetadataInterface|null $type
     *
     * @return mixed
     */
    private function navigate(
        $data,
        $direction,
        $format,
        ContextInterface $context = null,
        TypeMetadataInterface $type = null
    ) {
        $visitor = $this->visitorRegistry->getVisitor($direction, $format);

        $context = $context ?: new Context();
        $context->initialize($this->navigator, $visitor, $direction, $format);
        $this->navigator->navigate($visitor->prepare($data, $context), $context, $type);

        return $visitor->getResult();
    }
}
