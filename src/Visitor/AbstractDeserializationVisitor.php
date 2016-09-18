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

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Exclusion\ExclusionStrategyInterface;
use Ivory\Serializer\Instantiator\InstantiatorInterface;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mutator\MutatorInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractDeserializationVisitor extends AbstractVisitor
{
    /**
     * @var InstantiatorInterface
     */
    private $instantiator;

    /**
     * @var MutatorInterface
     */
    private $mutator;

    /**
     * @param InstantiatorInterface           $instantiator
     * @param MutatorInterface                $mutator
     * @param ExclusionStrategyInterface|null $exclusionStrategy
     */
    public function __construct(
        InstantiatorInterface $instantiator,
        MutatorInterface $mutator,
        ExclusionStrategyInterface $exclusionStrategy = null
    ) {
        parent::__construct($exclusionStrategy);

        $this->instantiator = $instantiator;
        $this->mutator = $mutator;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($data)
    {
        return $this->decode($data);
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    abstract protected function decode($data);

    /**
     * {@inheritdoc}
     */
    protected function doVisitObjectProperty(
        $data,
        $name,
        PropertyMetadataInterface $property,
        ContextInterface $context
    ) {
        if (!array_key_exists($name, $data)) {
            return false;
        }

        // FIXME - Detect errors
        $this->mutator->setValue(
            $this->result,
            $property->getName(),
            $this->navigate($data[$name], $property->getType(), $context)
        );

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function createResult(ClassMetadataInterface $classMetadata)
    {
        return $this->instantiator->instantiate($classMetadata->getName());
    }
}
