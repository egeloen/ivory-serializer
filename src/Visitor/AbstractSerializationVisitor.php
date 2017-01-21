<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Visitor;

use Ivory\Serializer\Accessor\AccessorInterface;
use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractSerializationVisitor extends AbstractGenericVisitor
{
    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @param AccessorInterface $accessor
     */
    public function __construct(AccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->encode($this->result);
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    abstract protected function encode($data);

    /**
     * {@inheritdoc}
     */
    protected function doVisitObjectProperty(
        $data,
        $name,
        PropertyMetadataInterface $property,
        ContextInterface $context
    ) {
        if (!$property->isReadable()) {
            return false;
        }

        $result = $this->navigator->navigate(
            $this->accessor->getValue(
                $data,
                $property->hasAccessor() ? $property->getAccessor() : $property->getName()
            ),
            $context,
            $property->getType()
        );

        if ($result === null && $context->isNullIgnored()) {
            return false;
        }

        $this->result[$name] = $result;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function createResult($class)
    {
        return [];
    }
}
