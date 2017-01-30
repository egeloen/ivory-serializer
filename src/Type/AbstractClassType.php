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
use Ivory\Serializer\Direction;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractClassType implements TypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        if ($context->getDirection() === Direction::SERIALIZATION) {
            return $this->serialize($data, $type, $context);
        }

        return $this->deserialize($data, $type, $context);
    }

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    abstract protected function serialize($data, TypeMetadataInterface $type, ContextInterface $context);

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    abstract protected function deserialize($data, TypeMetadataInterface $type, ContextInterface $context);
}
