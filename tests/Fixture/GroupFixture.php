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
class GroupFixture implements FixtureInterface
{
    /**
     * @Serializer\Groups({"group1", "group2"})
     *
     * @var bool
     */
    private $foo;

    /**
     * @Serializer\Groups({"group1"})
     *
     * @var bool
     */
    private $bar;

    /**
     * @Serializer\Groups({"group2"})
     *
     * @var bool
     */
    private $baz;

    /**
     * @var bool
     */
    private $bat;

    /**
     * @return bool
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param bool $foo
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return bool
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param bool $bar
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
    }

    /**
     * @return bool
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @param bool $baz
     */
    public function setBaz($baz)
    {
        $this->baz = $baz;
    }

    /**
     * @return bool
     */
    public function isBat()
    {
        return $this->bat;
    }

    /**
     * @param bool $bat
     */
    public function setBat($bat)
    {
        $this->bat = $bat;
    }

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
