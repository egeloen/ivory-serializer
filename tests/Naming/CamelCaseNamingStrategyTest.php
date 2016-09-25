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

use Ivory\Serializer\Naming\CamelCaseNamingStrategy;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class CamelCaseNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CamelCaseNamingStrategy
     */
    private $strategy;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->strategy = new CamelCaseNamingStrategy();
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
            ['fooBar', 'fooBar'],
            ['fooBar', 'FooBar'],
            ['fooBar', 'foo_bar'],
            ['fooBar', 'foo__bar'],
            ['fooBar', 'foo-bar'],
            ['fooBar', 'foo--bar'],
            ['fooBar', 'foo bar'],
            ['fooBar', 'foo  bar'],
        ];
    }
}
