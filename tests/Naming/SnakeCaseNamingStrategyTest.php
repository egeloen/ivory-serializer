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

use Ivory\Serializer\Naming\SnakeCaseNamingStrategy;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class SnakeCaseNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SnakeCaseNamingStrategy
     */
    private $strategy;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->strategy = new SnakeCaseNamingStrategy();
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
            ['foo', 'foo'],
            ['foo_bar', 'foo_bar'],
            ['foo_bar', 'foo__bar'],
            ['foo_bar', 'FooBar'],
            ['foo_bar', 'fooBar'],
            ['foo_bar', 'foo-bar'],
            ['foo_bar', 'foo--bar'],
            ['foo_bar', 'foo bar'],
            ['foo_bar', 'foo  bar'],
        ];
    }
}
