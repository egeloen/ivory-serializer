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
interface ExclusionStrategyInterface
{
    /**
     * @param ClassMetadataInterface $class
     * @param ContextInterface       $context
     *
     * @return bool
     */
    public function skipClass(ClassMetadataInterface $class, ContextInterface $context);

    /**
     * @param PropertyMetadataInterface $property
     * @param ContextInterface          $context
     *
     * @return bool
     */
    public function skipProperty(PropertyMetadataInterface $property, ContextInterface $context);

    /**
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return bool
     */
    public function skipType(TypeMetadataInterface $type, ContextInterface $context);
}
