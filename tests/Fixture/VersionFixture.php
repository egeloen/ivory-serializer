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
 * @author GeLo <geloen.eric@gmail.com>
 */
class VersionFixture implements FixtureInterface
{
    /**
     * @Serializer\Since("1.0")
     * @Serializer\Until("2.0")
     *
     * @var bool
     */
    public $foo;

    /**
     * @Serializer\Since("1.0")
     *
     * @var bool
     */
    public $bar;

    /**
     * @Serializer\Until("2.0")
     *
     * @var bool
     */
    public $baz;

    /**
     * @var bool
     */
    public $bat;

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,
            'baz' => $this->baz,
            'bat' => $this->bat,
        ];
    }
}
