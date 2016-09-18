<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mutator;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
interface MutatorInterface
{
    /**
     * @param object $object
     * @param string $property
     * @param mixed  $value
     */
    public function setValue($object, $property, $value);
}
