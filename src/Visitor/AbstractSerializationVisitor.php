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
use Ivory\Serializer\Exclusion\ExclusionStrategyInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Naming\NamingStrategyInterface;

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
     * @param AccessorInterface               $accessor
     * @param ExclusionStrategyInterface|null $exclusionStrategy
     * @param NamingStrategyInterface|null    $namingStrategy
     */
    public function __construct(
        AccessorInterface $accessor,
        ExclusionStrategyInterface $exclusionStrategy = null,
        NamingStrategyInterface $namingStrategy = null
    ) {
        parent::__construct($exclusionStrategy, $namingStrategy);

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
        // FIXME - Detect errors
        $this->result[$property->getName()] = $this->navigate(
            $this->accessor->getValue($data, $name),
            $property->getType(),
            $context
        );

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
