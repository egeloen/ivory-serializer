<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Fixture;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
interface FixtureInterface
{
    /**
     * @param mixed[] $options
     *
     * @return mixed[]
     */
    public function toArray(array $options = []);
}
