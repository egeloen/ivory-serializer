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
abstract class AbstractClassType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    protected function doConvert($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        switch ($context->getDirection()) {
            case Direction::SERIALIZATION:
                $data = $this->serialize($data, $type, $context);
                break;

            case Direction::DESERIALIZATION:
                $data = $this->deserialize($data, $type, $context);
                break;
        }

        return $context->getVisitor()->visitData($data, $type, $context);
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
