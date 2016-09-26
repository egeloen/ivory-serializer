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
class IgnoreNullFixture implements FixtureInterface
{
    /**
     * @var string
     */
    public $foo;

    /**
     * @var int
     */
    public $bar;

    /**
     * @var string[]
     */
    public $baz;

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,
            'baz' => $this->baz,
        ];
    }
}
