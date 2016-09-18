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
class ExclusionStrategy implements ExclusionStrategyInterface
{
    /**
     * @param ExclusionStrategyInterface[] $strategies
     *
     * @return ExclusionStrategyInterface
     */
    public static function create(array $strategies = [])
    {
        if (empty($strategies)) {
            $strategies = [
                new GroupExclusionStrategy(),
                new MaxDepthExclusionStrategy(),
                new VersionExclusionStrategy(),
            ];
        }

        return count($strategies) > 1 ? new ChainExclusionStrategy($strategies) : array_shift($strategies);
    }

    /**
     * {@inheritdoc}
     */
    public function skipClass(ClassMetadataInterface $class, ContextInterface $context)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function skipProperty(PropertyMetadataInterface $property, ContextInterface $context)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function skipType(TypeMetadataInterface $type, ContextInterface $context)
    {
        return false;
    }
}
