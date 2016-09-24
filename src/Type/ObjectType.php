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
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactory;
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ObjectType implements TypeInterface
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    private $classMetadataFactory;

    /**
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     */
    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory = null)
    {
        $this->classMetadataFactory = $classMetadataFactory ?: ClassMetadataFactory::create();
    }

    /**
     * {@inheritdoc}
     */
    public function convert($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $class = $this->classMetadataFactory->getClassMetadata($type->getName());

        if ($class === null) {
            throw new \RuntimeException(sprintf('The class metadata "%s" does not exit.', $type->getName()));
        }

        $visitor = $context->getVisitor();

        if (!$visitor->startVisitingObject($data, $class, $context)) {
            return $visitor->visitNull(null, $type, $context);
        }

        foreach ($class->getProperties() as $property) {
            $visitor->visitObjectProperty($data, $property, $context);
        }

        return $visitor->finishVisitingObject($data, $class, $context);
    }
}
