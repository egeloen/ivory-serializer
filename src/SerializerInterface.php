<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
interface SerializerInterface
{
    /**
     * @param mixed                 $data
     * @param string                $format
     * @param ContextInterface|null $context
     *
     * @return string
     */
    public function serialize($data, $format, ContextInterface $context = null);

    /**
     * @param string                       $data
     * @param TypeMetadataInterface|string $type
     * @param string                       $format
     * @param ContextInterface|null        $context
     *
     * @return mixed
     */
    public function deserialize($data, $type, $format, ContextInterface $context = null);
}
