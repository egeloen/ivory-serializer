<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Mapping;

use Ivory\Serializer\Mapping\ClassMetadata;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var string
     */
    private $name;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->name = \stdClass::class;
        $this->classMetadata = new ClassMetadata($this->name);
    }

    public function testInheritance()
    {
        $this->assertInstanceOf(ClassMetadataInterface::class, $this->classMetadata);
    }

    public function testDefaultState()
    {
        $this->assertSame($this->name, $this->classMetadata->getName());
        $this->assertFalse($this->classMetadata->hasProperties());
        $this->assertEmpty($this->classMetadata->getProperties());
    }

    public function testSetProperties()
    {
        $properties = [$name = 'foo' => $property = $this->createPropertyMetadataMock($name)];

        $this->classMetadata->setProperties($properties);
        $this->classMetadata->setProperties($properties);

        $this->assertTrue($this->classMetadata->hasProperties());
        $this->assertTrue($this->classMetadata->hasProperty($name));
        $this->assertSame($property, $this->classMetadata->getProperty($name));
        $this->assertSame($properties, $this->classMetadata->getProperties());
    }

    public function testAddProperty()
    {
        $this->classMetadata->addProperty($property = $this->createPropertyMetadataMock($name = 'foo'));

        $this->assertTrue($this->classMetadata->hasProperties());
        $this->assertTrue($this->classMetadata->hasProperty($name));
        $this->assertSame($property, $this->classMetadata->getProperty($name));
        $this->assertSame([$name => $property], $this->classMetadata->getProperties());
    }

    public function testAddPropertyMerge()
    {
        $firstProperty = $this->createPropertyMetadataMock($name = 'foo');
        $secondProperty = $this->createPropertyMetadataMock($name);

        $firstProperty
            ->expects($this->once())
            ->method('merge')
            ->with($this->identicalTo($secondProperty));

        $this->classMetadata->addProperty($firstProperty);
        $this->classMetadata->addProperty($secondProperty);

        $this->assertTrue($this->classMetadata->hasProperties());
        $this->assertTrue($this->classMetadata->hasProperty($name));
        $this->assertSame($firstProperty, $this->classMetadata->getProperty($name));
        $this->assertNotSame($secondProperty, $this->classMetadata->getProperty($name));
        $this->assertSame([$name => $firstProperty], $this->classMetadata->getProperties());
    }

    public function testRemoveProperty()
    {
        $this->classMetadata->addProperty($property = $this->createPropertyMetadataMock($name = 'foo'));
        $this->classMetadata->removeProperty($name);

        $this->assertFalse($this->classMetadata->hasProperties());
        $this->assertFalse($this->classMetadata->hasProperty($name));
        $this->assertNull($this->classMetadata->getProperty($name));
        $this->assertEmpty($this->classMetadata->getProperties());
    }

    public function testMerge()
    {
        $firstProperty = $this->createPropertyMetadataMock($firstName = 'foo');
        $secondProperty = $this->createPropertyMetadataMock($firstName);
        $thirdProperty = $this->createPropertyMetadataMock($thirdName = 'bar');

        $classMetadata = new ClassMetadata(\stdClass::class);
        $classMetadata->addProperty($secondProperty);
        $classMetadata->addProperty($thirdProperty);

        $firstProperty
            ->expects($this->once())
            ->method('merge')
            ->with($this->identicalTo($secondProperty));

        $this->classMetadata->addProperty($firstProperty);
        $this->classMetadata->merge($classMetadata);

        $this->assertSame(
            [$firstName => $firstProperty, $thirdName => $thirdProperty],
            $this->classMetadata->getProperties()
        );
    }

    public function testSerialize()
    {
        $this->assertEquals($this->classMetadata, unserialize(serialize($this->classMetadata)));
    }

    /**
     * @param string $name
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|PropertyMetadataInterface
     */
    private function createPropertyMetadataMock($name)
    {
        $propertyMetadata = $this->createMock(PropertyMetadataInterface::class);
        $propertyMetadata
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($name));

        return $propertyMetadata;
    }
}
