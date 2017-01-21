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
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class StdClassType extends AbstractClassType
{
    /**
     * {@inheritdoc}
     */
    protected function serialize($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $this->visit((array) $data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    protected function deserialize($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return (object) $this->visit($data, $type, $context);
    }

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    private function visit($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $context->getVisitor()->visitArray($data, $type, $context);
    }
}
