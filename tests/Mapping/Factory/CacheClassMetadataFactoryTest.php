<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Mapping\Factory;

use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\Factory\CacheClassMetadataFactory;
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class CacheClassMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CacheClassMetadataFactory
     */
    private $cacheFactory;

    /**
     * @var ClassMetadataFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    /**
     * @var CacheItemPoolInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $pool;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->factory = $this->createClassMetadataFactoryMock();
        $this->pool = $this->createCacheItemPoolMock();

        $this->cacheFactory = new CacheClassMetadataFactory($this->factory, $this->pool);
    }

    public function testInheritance()
    {
        $this->assertInstanceOf(ClassMetadataFactoryInterface::class, $this->cacheFactory);
    }

    public function testClassMetadata()
    {
        $this->pool
            ->expects($this->once())
            ->method('getItem')
            ->with($this->identicalTo('foo_bar'))
            ->will($this->returnValue($item = $this->createCacheItemMock()));

        $item
            ->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(false));

        $this->factory
            ->expects($this->once())
            ->method('getClassMetadata')
            ->with($this->identicalTo($class = 'foo\bar'))
            ->will($this->returnValue($classMetadata = $this->createClassMetadataMock()));

        $item
            ->expects($this->once())
            ->method('set')
            ->with($this->identicalTo($classMetadata))
            ->will($this->returnSelf());

        $this->pool
            ->expects($this->once())
            ->method('save')
            ->with($this->identicalTo($item));

        $this->assertSame($classMetadata, $this->cacheFactory->getClassMetadata($class));
    }

    public function testClassMetadataCached()
    {
        $this->pool
            ->expects($this->once())
            ->method('getItem')
            ->with($this->identicalTo('foo_bar'))
            ->will($this->returnValue($item = $this->createCacheItemMock()));

        $item
            ->expects($this->once())
            ->method('isHit')
            ->will($this->returnValue(true));

        $item
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($classMetadata = $this->createClassMetadataMock()));

        $this->factory
            ->expects($this->never())
            ->method('getClassMetadata');

        $this->assertSame($classMetadata, $this->cacheFactory->getClassMetadata('foo\bar'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClassMetadataFactoryInterface
     */
    private function createClassMetadataFactoryMock()
    {
        return $this->createMock(ClassMetadataFactoryInterface::class);
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClassMetadataInterface
     */
    private function createClassMetadataMock()
    {
        return $this->createMock(ClassMetadataInterface::class);
    }
}
