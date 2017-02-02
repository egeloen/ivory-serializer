<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Type\Guesser;

use Ivory\Serializer\Mapping\TypeMetadata;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class TypeGuesser implements TypeGuesserInterface
{
    /**
     * {@inheritdoc}
     */
    public function guess($data)
    {
        return new TypeMetadata(is_object($data) ? get_class($data) : strtolower(gettype($data)));
    }
}
