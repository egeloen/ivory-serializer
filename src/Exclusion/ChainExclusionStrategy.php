<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Exclusion;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ChainExclusionStrategy implements ExclusionStrategyInterface
{
    /**
     * @var ExclusionStrategyInterface[]
     */
    private $strategies = [];

    /**
     * @param ExclusionStrategyInterface[] $strategies
     */
    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * {@inheritdoc}
     */
    public function skipClass(ClassMetadataInterface $class, ContextInterface $context)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->skipClass($class, $context)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function skipProperty(PropertyMetadataInterface $property, ContextInterface $context)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->skipProperty($property, $context)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function skipType(TypeMetadataInterface $type, ContextInterface $context)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->skipType($type, $context)) {
                return true;
            }
        }

        return false;
    }
}
