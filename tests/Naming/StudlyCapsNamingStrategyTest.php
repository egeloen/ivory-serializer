<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Naming;

use Ivory\Serializer\Naming\StudlyCapsNamingStrategy;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class StudlyCapsNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StudlyCapsNamingStrategy
     */
    private $strategy;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->strategy = new StudlyCapsNamingStrategy();
    }

    /**
     * @param string $expected
     * @param string $name
     *
     * @dataProvider convertProvider
     */
    public function testConvert($expected, $name)
    {
        $this->assertSame($expected, $this->strategy->convert($name));
    }

    /**
     * @return string[][]
     */
    public function convertProvider()
    {
        return [
            ['Foo', 'foo'],
            ['FooBar', 'FooBar'],
            ['FooBar', 'fooBar'],
            ['FooBar', 'foo_bar'],
            ['FooBar', 'foo__bar'],
            ['FooBar', 'foo-bar'],
            ['FooBar', 'foo--bar'],
            ['FooBar', 'foo bar'],
            ['FooBar', 'foo  bar'],
        ];
    }
}
