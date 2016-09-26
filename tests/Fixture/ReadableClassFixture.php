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

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @Serializer\Readable(false)
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class ReadableClassFixture implements FixtureInterface
{
    /**
     * @Serializer\Readable(true)
     *
     * @var string
     */
    public $foo;

    /**
     * @var string
     */
    public $bar;

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        return [
            'foo' => $this->foo,
            'bar' => null,
        ];
    }
}
