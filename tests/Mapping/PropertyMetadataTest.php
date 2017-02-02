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

use Ivory\Serializer\Mapping\PropertyMetadata;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class PropertyMetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PropertyMetadata
     */
    private $propertyMetadata;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $class;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->name = 'foo';
        $this->class = 'bar';

        $this->propertyMetadata = new PropertyMetadata($this->name, $this->class);
    }

    public function testInheritance()
    {
        $this->assertInstanceOf(PropertyMetadataInterface::class, $this->propertyMetadata);
    }

    public function testDefaultState()
    {
        $this->assertSame($this->name, $this->propertyMetadata->getName());
        $this->assertSame($this->class, $this->propertyMetadata->getClass());
        $this->assertFalse($this->propertyMetadata->hasAlias());
        $this->assertNull($this->propertyMetadata->getAlias());
        $this->assertFalse($this->propertyMetadata->hasType());
        $this->assertNull($this->propertyMetadata->getType());
        $this->assertTrue($this->propertyMetadata->isReadable());
        $this->assertTrue($this->propertyMetadata->isWritable());
        $this->assertFalse($this->propertyMetadata->hasAccessor());
        $this->assertNull($this->propertyMetadata->getAccessor());
        $this->assertFalse($this->propertyMetadata->hasMutator());
        $this->assertNull($this->propertyMetadata->getMutator());
        $this->assertFalse($this->propertyMetadata->hasSinceVersion());
        $this->assertNull($this->propertyMetadata->getSinceVersion());
        $this->assertFalse($this->propertyMetadata->hasUntilVersion());
        $this->assertNull($this->propertyMetadata->getUntilVersion());
        $this->assertFalse($this->propertyMetadata->hasMaxDepth());
        $this->assertNull($this->propertyMetadata->getMaxDepth());
        $this->assertFalse($this->propertyMetadata->hasGroups());
        $this->assertEmpty($this->propertyMetadata->getGroups());
        $this->assertFalse($this->propertyMetadata->hasXmlAttribute());
        $this->assertFalse($this->propertyMetadata->isXmlAttribute());
        $this->assertFalse($this->propertyMetadata->hasXmlValue());
        $this->assertFalse($this->propertyMetadata->isXmlValue());
        $this->assertFalse($this->propertyMetadata->hasXmlInline());
        $this->assertFalse($this->propertyMetadata->isXmlInline());
        $this->assertFalse($this->propertyMetadata->hasXmlEntry());
        $this->assertNull($this->propertyMetadata->getXmlEntry());
        $this->assertFalse($this->propertyMetadata->hasXmlEntryAttribute());
        $this->assertNull($this->propertyMetadata->getXmlEntryAttribute());
        $this->assertFalse($this->propertyMetadata->hasXmlKeyAsAttribute());
        $this->assertNull($this->propertyMetadata->useXmlKeyAsAttribute());
        $this->assertFalse($this->propertyMetadata->hasXmlKeyAsNode());
        $this->assertNull($this->propertyMetadata->useXmlKeyAsNode());
    }

    public function testName()
    {
        $this->propertyMetadata->setName($name = 'bar');

        $this->assertSame($name, $this->propertyMetadata->getName());
    }

    public function tesClass()
    {
        $this->propertyMetadata->setClass($name = 'baz');

        $this->assertSame($name, $this->propertyMetadata->getClass());
    }

    public function testAlias()
    {
        $this->propertyMetadata->setAlias($alias = 'bar');

        $this->assertTrue($this->propertyMetadata->hasAlias());
        $this->assertSame($alias, $this->propertyMetadata->getAlias());
    }

    public function testType()
    {
        $this->propertyMetadata->setType($type = $this->createTypeMetadataMock());

        $this->assertTrue($this->propertyMetadata->hasType());
        $this->assertSame($type, $this->propertyMetadata->getType());
    }

    public function testReadable()
    {
        $this->propertyMetadata->setReadable(false);

        $this->assertFalse($this->propertyMetadata->isReadable());
    }

    public function testWritable()
    {
        $this->propertyMetadata->setWritable(false);

        $this->assertFalse($this->propertyMetadata->isWritable());
    }

    public function testAccessor()
    {
        $this->propertyMetadata->setAccessor($accessor = 'foo');

        $this->assertTrue($this->propertyMetadata->hasAccessor());
        $this->assertSame($accessor, $this->propertyMetadata->getAccessor());
    }

    public function testMutator()
    {
        $this->propertyMetadata->setMutator($mutator = 'foo');

        $this->assertTrue($this->propertyMetadata->hasMutator());
        $this->assertSame($mutator, $this->propertyMetadata->getMutator());
    }

    public function testSinceVersion()
    {
        $this->propertyMetadata->setSinceVersion($since = '1.0');

        $this->assertTrue($this->propertyMetadata->hasSinceVersion());
        $this->assertSame($since, $this->propertyMetadata->getSinceVersion());
    }

    public function testUntilVersion()
    {
        $this->propertyMetadata->setUntilVersion($until = '1.0');

        $this->assertTrue($this->propertyMetadata->hasUntilVersion());
        $this->assertSame($until, $this->propertyMetadata->getUntilVersion());
    }

    public function testMaxDepth()
    {
        $this->propertyMetadata->setMaxDepth($maxDepth = 512);

        $this->assertTrue($this->propertyMetadata->hasMaxDepth());
        $this->assertSame($maxDepth, $this->propertyMetadata->getMaxDepth());
    }

    public function testSetGroups()
    {
        $this->propertyMetadata->setGroups($groups = [$group = 'group']);
        $this->propertyMetadata->setGroups($groups);

        $this->assertTrue($this->propertyMetadata->hasGroups());
        $this->assertTrue($this->propertyMetadata->hasGroup($group));
        $this->assertSame($groups, $this->propertyMetadata->getGroups());
    }

    public function testAddGroups()
    {
        $this->propertyMetadata->setGroups($firstGroups = ['group1']);
        $this->propertyMetadata->addGroups($secondGroups = ['group2']);

        $this->assertTrue($this->propertyMetadata->hasGroups());
        $this->assertSame(array_merge($firstGroups, $secondGroups), $this->propertyMetadata->getGroups());
    }

    public function testAddGroup()
    {
        $this->propertyMetadata->addGroup($group = 'group');

        $this->assertTrue($this->propertyMetadata->hasGroups());
        $this->assertTrue($this->propertyMetadata->hasGroup($group));
        $this->assertSame([$group], $this->propertyMetadata->getGroups());
    }

    public function testRemoveGroup()
    {
        $this->propertyMetadata->addGroup($group = 'group');
        $this->propertyMetadata->removeGroup($group);

        $this->assertFalse($this->propertyMetadata->hasGroups());
        $this->assertFalse($this->propertyMetadata->hasGroup($group));
        $this->assertEmpty($this->propertyMetadata->getGroups());
    }

    public function testXmlAttribute()
    {
        $this->propertyMetadata->setXmlAttribute(true);

        $this->assertTrue($this->propertyMetadata->hasXmlAttribute());
        $this->assertTrue($this->propertyMetadata->isXmlAttribute());
    }

    public function testXmlValue()
    {
        $this->propertyMetadata->setXmlValue(true);

        $this->assertTrue($this->propertyMetadata->hasXmlValue());
        $this->assertTrue($this->propertyMetadata->isXmlValue());
    }

    public function testXmlInline()
    {
        $this->propertyMetadata->setXmlInline(true);

        $this->assertTrue($this->propertyMetadata->hasXmlInline());
        $this->assertTrue($this->propertyMetadata->isXmlInline());
    }

    public function testXmlEntry()
    {
        $this->propertyMetadata->setXmlEntry($entry = 'entry');

        $this->assertTrue($this->propertyMetadata->hasXmlEntry());
        $this->assertSame($entry, $this->propertyMetadata->getXmlEntry());
    }

    public function testXmlEntryAttribute()
    {
        $this->propertyMetadata->setXmlEntryAttribute($entryAttribute = 'key');

        $this->assertTrue($this->propertyMetadata->hasXmlEntryAttribute());
        $this->assertSame($entryAttribute, $this->propertyMetadata->getXmlEntryAttribute());
    }

    public function testXmlKeyAsAttribute()
    {
        $this->propertyMetadata->setXmlKeyAsAttribute(true);

        $this->assertTrue($this->propertyMetadata->hasXmlKeyAsAttribute());
        $this->assertTrue($this->propertyMetadata->useXmlKeyAsAttribute());
    }

    public function testXmlKeyAsNode()
    {
        $this->propertyMetadata->setXmlKeyAsNode(true);

        $this->assertTrue($this->propertyMetadata->hasXmlKeyAsNode());
        $this->assertTrue($this->propertyMetadata->useXmlKeyAsNode());
    }

    public function testMerge()
    {
        $propertyMetadata = new PropertyMetadata($name = 'foo', $class = 'bar');
        $propertyMetadata->setAlias($alias = 'baz');
        $propertyMetadata->setType($type = $this->createTypeMetadataMock());
        $propertyMetadata->setReadable(true);
        $propertyMetadata->setWritable(false);
        $propertyMetadata->setAccessor($accessor = 'getFoo');
        $propertyMetadata->setMutator($mutator = 'setFoo');
        $propertyMetadata->setSinceVersion($sinceVersion = '1.0');
        $propertyMetadata->setUntilVersion($untilVersion = '2.0');
        $propertyMetadata->setMaxDepth($maxDepth = 1);
        $propertyMetadata->setGroups($groups = ['group1', 'group2']);
        $propertyMetadata->setXmlAttribute(true);
        $propertyMetadata->setXmlValue(true);
        $propertyMetadata->setXmlInline(true);
        $propertyMetadata->setXmlEntry($entry = 'entry');
        $propertyMetadata->setXmlEntryAttribute($entryAttribute = 'key');
        $propertyMetadata->setXmlKeyAsAttribute(true);
        $propertyMetadata->setXmlKeyAsNode(true);

        $this->propertyMetadata->merge($propertyMetadata);

        $this->assertSame($name, $this->propertyMetadata->getName());
        $this->assertSame($class, $this->propertyMetadata->getClass());
        $this->assertSame($alias, $this->propertyMetadata->getAlias());
        $this->assertSame($type, $this->propertyMetadata->getType());
        $this->assertTrue($this->propertyMetadata->isReadable());
        $this->assertFalse($this->propertyMetadata->isWritable());
        $this->assertSame($accessor, $this->propertyMetadata->getAccessor());
        $this->assertSame($mutator, $this->propertyMetadata->getMutator());
        $this->assertSame($sinceVersion, $this->propertyMetadata->getSinceVersion());
        $this->assertSame($untilVersion, $this->propertyMetadata->getUntilVersion());
        $this->assertSame($maxDepth, $this->propertyMetadata->getMaxDepth());
        $this->assertSame($groups, $this->propertyMetadata->getGroups());
        $this->assertTrue($this->propertyMetadata->isXmlAttribute());
        $this->assertTrue($this->propertyMetadata->isXmlValue());
        $this->assertTrue($this->propertyMetadata->isXmlInline());
        $this->assertSame($entry, $this->propertyMetadata->getXmlEntry());
        $this->assertSame($entryAttribute, $this->propertyMetadata->getXmlEntryAttribute());
        $this->assertTrue($this->propertyMetadata->useXmlKeyAsAttribute());
        $this->assertTrue($this->propertyMetadata->useXmlKeyAsNode());
    }

    public function testSerialize()
    {
        $this->assertEquals($this->propertyMetadata, unserialize(serialize($this->propertyMetadata)));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|TypeMetadataInterface
     */
    private function createTypeMetadataMock()
    {
        return $this->createMock(TypeMetadataInterface::class);
    }
}
