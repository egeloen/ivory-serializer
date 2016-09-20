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
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Mutator\MutatorInterface;
use Ivory\Serializer\Naming\NamingStrategyInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractDeserializationVisitor extends AbstractGenericVisitor
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
     * @param NamingStrategyInterface|null    $namingStrategy
     */
    public function __construct(
        InstantiatorInterface $instantiator,
        MutatorInterface $mutator,
        ExclusionStrategyInterface $exclusionStrategy = null,
        NamingStrategyInterface $namingStrategy = null
    ) {
        parent::__construct($exclusionStrategy, $namingStrategy);

        $this->instantiator = $instantiator;
        $this->mutator = $mutator;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($data)
    {
        return $this->decode(parent::prepare($data));
    }

    /**
     * {@inheritdoc}
     */
    public function visitBoolean($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return parent::visitBoolean(
            filter_var($data, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            $type,
            $context
        );
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
    protected function createResult($class)
    {
        return $this->instantiator->instantiate($class);
    }
}
