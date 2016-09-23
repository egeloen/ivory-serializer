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
 * @Serializer\Order("DESC")
 * 
 * @author GeLo <geloen.eric@gmail.com>
 */
class DescFixture implements FixtureInterface
{
    /**
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
            'bar' => $this->bar,
            'foo' => $this->foo,
        ];
    }
}
