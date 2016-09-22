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

use Ivory\Serializer\Mapping\ClassMetadata;
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactory;
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Ivory\Serializer\Mapping\Loader\ClassMetadataLoaderInterface;
use Ivory\Tests\Serializer\Fixture\ExtendedScalarFixture;
use Ivory\Tests\Serializer\Fixture\ScalarFixture;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ClassMetadataFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassMetadataFactory
     */
    private $factory;

    /**
     * @var ClassMetadataLoaderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loader;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->loader = $this->createClassMetadataLoaderMock();
        $this->factory = new ClassMetadataFactory($this->loader);
    }

    public function testInheritance()
    {
        $this->assertInstanceOf(ClassMetadataFactoryInterface::class, $this->factory);
    }

    public function testClassMetadata()
    {
        $class = \stdClass::class;
        $expected = null;

        $this->loader
            ->expects($this->once())
            ->method('loadClassMetadata')
            ->with($this->callback(function ($classMetadata) use ($class, &$expected) {
                $expected = $classMetadata;

                return $classMetadata instanceof ClassMetadata && $classMetadata->getName() === $class;
            }))
            ->will($this->returnValue(true));

        $firstResult = $this->factory->getClassMetadata($class);
        $secondResult = $this->factory->getClassMetadata($class);

        $this->assertSame($expected, $firstResult);
        $this->assertSame($expected, $secondResult);
    }

    public function testClassMetadataInheritance()
    {
        $expected = [];

        $this->loader
            ->expects($this->exactly(2))
            ->method('loadClassMetadata')
            ->with($this->callback(function ($classMetadata) use (&$expected) {
                $expected[] = $classMetadata;

                return $classMetadata instanceof ClassMetadata;
            }))
            ->will($this->returnValue(true));

        $firstResult = $this->factory->getClassMetadata($class = ExtendedScalarFixture::class);
        $secondResult = $this->factory->getClassMetadata($class);

        $this->assertArrayHasKey(0, $expected);
        $this->assertSame($class, $expected[0]->getName());

        $this->assertSame($expected[0], $firstResult);
        $this->assertSame($expected[0], $secondResult);

        $this->assertArrayHasKey(1, $expected);
        $this->assertSame(ScalarFixture::class, $expected[1]->getName());
    }

    public function testClassMetadataDoesNotExist()
    {
        $class = \stdClass::class;

        $this->loader
            ->expects($this->once())
            ->method('loadClassMetadata')
            ->with($this->callback(function ($classMetadata) use ($class) {
                return $classMetadata instanceof ClassMetadata && $classMetadata->getName() === $class;
            }))
            ->will($this->returnValue(false));

        $this->assertNull($this->factory->getClassMetadata($class));
        $this->assertNull($this->factory->getClassMetadata($class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testClassMetadataInvalid()
    {
        $this->factory->getClassMetadata('foo');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClassMetadataLoaderInterface
     */
    private function createClassMetadataLoaderMock()
    {
        return $this->createMock(ClassMetadataLoaderInterface::class);
    }
}
