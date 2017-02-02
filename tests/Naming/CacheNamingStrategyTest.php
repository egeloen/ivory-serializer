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

use Ivory\Serializer\Naming\CacheNamingStrategy;
use Ivory\Serializer\Naming\NamingStrategyInterface;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class CacheNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CacheNamingStrategy
     */
    private $cacheNamingStrategy;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|NamingStrategyInterface
     */
    private $namingStrategy;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CacheItemPoolInterface
     */
    private $pool;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->pool = $this->createCacheItemPoolMock();
        $this->namingStrategy = $this->createNamingStrategyMock();

        $this->cacheNamingStrategy = new CacheNamingStrategy($this->namingStrategy, $this->pool);
    }

    public function testCacheHit()
    {
        $this->pool
            ->expects($this->once())
            ->method('getItem')
            ->with($this->identicalTo($name = 'fooBar'))
            ->will($this->returnValue($item = $this->createCacheItemMock()));

        $item
            ->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(true));

        $item
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($result = 'foo_bar'));

        $this->namingStrategy
            ->expects($this->never())
            ->method('convert');

        $this->assertSame($result, $this->cacheNamingStrategy->convert($name));
    }

    public function testCacheMiss()
    {
        $this->pool
            ->expects($this->once())
            ->method('getItem')
            ->with($this->identicalTo($name = 'fooBar'))
            ->will($this->returnValue($item = $this->createCacheItemMock()));

        $item
            ->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(false));

        $this->namingStrategy
            ->expects($this->once())
            ->method('convert')
            ->with($this->identicalTo($name))
            ->will($this->returnValue($result = 'foo_bar'));

        $item
            ->expects($this->once())
            ->method('set')
            ->with($this->identicalTo($result))
            ->will($this->returnSelf());

        $this->pool
            ->expects($this->once())
            ->method('save')
            ->with($this->identicalTo($item));

        $this->assertSame($result, $this->cacheNamingStrategy->convert($name));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|NamingStrategyInterface
     */
    private function createNamingStrategyMock()
    {
        return $this->createMock(NamingStrategyInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CacheItemPoolInterface
     */
    private function createCacheItemPoolMock()
    {
        return $this->createMock(CacheItemPoolInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|CacheItemInterface
     */
    private function createCacheItemMock()
    {
        return $this->createMock(CacheItemInterface::class);
    }
}
