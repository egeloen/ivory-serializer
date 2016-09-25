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

use Ivory\Serializer\Naming\KebabCaseNamingStrategy;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class KebabCaseNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var KebabCaseNamingStrategy
     */
    private $strategy;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->strategy = new KebabCaseNamingStrategy();
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
            ['foo-bar', 'foo-bar'],
            ['foo-bar', 'foo--bar'],
            ['foo-bar', 'FooBar'],
            ['foo-bar', 'fooBar'],
            ['foo-bar', 'foo_bar'],
            ['foo-bar', 'foo__bar'],
            ['foo-bar', 'foo bar'],
            ['foo-bar', 'foo  bar'],
        ];
    }
}
