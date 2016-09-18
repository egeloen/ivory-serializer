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

use Ivory\Serializer\Mapping\TypeMetadata;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Type\Type;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class TypeMetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TypeMetadata
     */
    private $typeMetadata;

    /**
     * @var string
     */
    private $name;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->name = Type::STRING;
        $this->typeMetadata = new TypeMetadata($this->name);
    }

    public function testInheritance()
    {
        $this->assertInstanceOf(TypeMetadataInterface::class, $this->typeMetadata);
    }

    public function testDefaultState()
    {
        $this->assertSame($this->name, $this->typeMetadata->getName());
        $this->assertFalse($this->typeMetadata->hasOptions());
        $this->assertEmpty($this->typeMetadata->getOptions());
    }

    public function testName()
    {
        $this->typeMetadata->setName($name = Type::BOOL);

        $this->assertSame($name, $this->typeMetadata->getName());
    }

    public function testSetOptions()
    {
        $this->typeMetadata->setOptions($options = [$option = 'foo' => $value = 'bar']);

        $this->assertTrue($this->typeMetadata->hasOptions());
        $this->assertTrue($this->typeMetadata->hasOption($option));
        $this->assertSame($options, $this->typeMetadata->getOptions());
        $this->assertSame($value, $this->typeMetadata->getOption($option));
    }

    public function testSetOption()
    {
        $this->typeMetadata->setOption($option = 'foo', $value = 'bar');

        $this->assertTrue($this->typeMetadata->hasOptions());
        $this->assertTrue($this->typeMetadata->hasOption($option));
        $this->assertSame([$option => $value], $this->typeMetadata->getOptions());
        $this->assertSame($value, $this->typeMetadata->getOption($option));
    }

    public function testRemoveOption()
    {
        $this->typeMetadata->setOption($option = 'foo', 'bar');
        $this->typeMetadata->removeOption($option);

        $this->assertFalse($this->typeMetadata->hasOptions());
        $this->assertFalse($this->typeMetadata->hasOption($option));
        $this->assertEmpty($this->typeMetadata->getOptions());
        $this->assertNull($this->typeMetadata->getOption($option));
        $this->assertSame('bat', $this->typeMetadata->getOption($option, 'bat'));
    }
}
