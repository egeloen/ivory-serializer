<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Mapping\Loader;

use Ivory\Serializer\Mapping\ClassMetadata;
use Ivory\Serializer\Mapping\Loader\ChainClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\ClassMetadataLoaderInterface;
use Ivory\Serializer\Mapping\Loader\MappedClassMetadataLoaderInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ChainClassMetadataLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChainClassMetadataLoader
     */
    private $loader;

    /**
     * @var ClassMetadataLoaderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $firstLoader;

    /**
     * @var MappedClassMetadataLoaderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $secondLoader;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->firstLoader = $this->createClassMetadataLoaderMock();
        $this->secondLoader = $this->createMappedClassMetadataLoaderMock();

        $this->loader = new ChainClassMetadataLoader([$this->firstLoader, $this->secondLoader]);
    }

    public function testInheritance()
    {
        $this->assertInstanceOf(ClassMetadataLoaderInterface::class, $this->loader);
    }

    public function testClassMetadata()
    {
        $classMetadata = new ClassMetadata(\stdClass::class);

        $this->firstLoader
            ->expects($this->once())
            ->method('loadClassMetadata')
            ->with($this->identicalTo($classMetadata))
            ->will($this->returnValue(false));

        $this->secondLoader
            ->expects($this->once())
            ->method('loadClassMetadata')
            ->with($this->identicalTo($classMetadata))
            ->will($this->returnValue(true));

        $this->assertTrue($this->loader->loadClassMetadata($classMetadata));
    }

    public function testClassMetadataNotFound()
    {
        $classMetadata = new ClassMetadata(\stdClass::class);

        $this->firstLoader
            ->expects($this->once())
            ->method('loadClassMetadata')
            ->with($this->identicalTo($classMetadata))
            ->will($this->returnValue(false));

        $this->secondLoader
            ->expects($this->once())
            ->method('loadClassMetadata')
            ->with($this->identicalTo($classMetadata))
            ->will($this->returnValue(false));

        $this->assertFalse($this->loader->loadClassMetadata($classMetadata));
    }

    public function testMappedClasses()
    {
        $this->secondLoader
            ->expects($this->once())
            ->method('getMappedClasses')
            ->will($this->returnValue($classes = [\stdClass::class]));

        $this->assertSame($classes, $this->loader->getMappedClasses());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClassMetadataLoaderInterface
     */
    private function createClassMetadataLoaderMock()
    {
        return $this->createMock(ClassMetadataLoaderInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|MappedClassMetadataLoaderInterface
     */
    private function createMappedClassMetadataLoaderMock()
    {
        return $this->createMock(MappedClassMetadataLoaderInterface::class);
    }
}
