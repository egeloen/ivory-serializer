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
use Ivory\Serializer\Mapping\Loader\MappedClassMetadataLoaderInterface;
use Ivory\Tests\Serializer\Fixture\AccessorFixture;
use Ivory\Tests\Serializer\Fixture\ArrayFixture;
use Ivory\Tests\Serializer\Fixture\AscFixture;
use Ivory\Tests\Serializer\Fixture\DateTimeFixture;
use Ivory\Tests\Serializer\Fixture\DescFixture;
use Ivory\Tests\Serializer\Fixture\ExcludeFixture;
use Ivory\Tests\Serializer\Fixture\ExposeFixture;
use Ivory\Tests\Serializer\Fixture\GroupFixture;
use Ivory\Tests\Serializer\Fixture\MaxDepthFixture;
use Ivory\Tests\Serializer\Fixture\MutatorFixture;
use Ivory\Tests\Serializer\Fixture\OrderFixture;
use Ivory\Tests\Serializer\Fixture\ReadableClassFixture;
use Ivory\Tests\Serializer\Fixture\ReadableFixture;
use Ivory\Tests\Serializer\Fixture\ScalarFixture;
use Ivory\Tests\Serializer\Fixture\VersionFixture;
use Ivory\Tests\Serializer\Fixture\WritableClassFixture;
use Ivory\Tests\Serializer\Fixture\WritableFixture;
use Ivory\Tests\Serializer\Fixture\XmlFixture;
use Ivory\Tests\Serializer\Fixture\XmlValueFixture;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractFileClassMetadataLoaderTest extends AbstractClassMetadataLoaderTest
{
    public function testInheritance()
    {
        $this->assertInstanceOf(MappedClassMetadataLoaderInterface::class, $this->loader);

        parent::testInheritance();
    }

    public function testArrayFixture()
    {
        $this->assertMappedClasses();

        parent::testArrayFixture();
    }

    public function testScalarFixture()
    {
        $this->assertMappedClasses();

        parent::testScalarFixture();
    }

    public function testDateTimeFixture()
    {
        $this->assertMappedClasses();

        parent::testDateTimeFixture();
    }

    public function testExcludeFixture()
    {
        $this->assertMappedClasses();

        parent::testExcludeFixture();
    }

    public function testExposeFixture()
    {
        $this->assertMappedClasses();

        parent::testExposeFixture();
    }

    public function testReadableFixture()
    {
        $this->assertMappedClasses();

        parent::testReadableFixture();
    }

    public function testReadableClassFixture()
    {
        $this->assertMappedClasses();

        parent::testReadableClassFixture();
    }

    public function testWritableFixture()
    {
        $this->assertMappedClasses();

        parent::testWritableFixture();
    }

    public function testWritableClassFixture()
    {
        $this->assertMappedClasses();

        parent::testWritableClassFixture();
    }

    public function testAccessorFixture()
    {
        $this->assertMappedClasses();

        parent::testAccessorFixture();
    }

    public function testMutatorFixture()
    {
        $this->assertMappedClasses();

        parent::testMutatorFixture();
    }

    public function testMaxDepthFixture()
    {
        $this->assertMappedClasses();

        parent::testMaxDepthFixture();
    }

    public function testGroupFixture()
    {
        $this->assertMappedClasses();

        parent::testGroupFixture();
    }

    public function testOrderFixture()
    {
        $this->assertMappedClasses();

        parent::testOrderFixture();
    }

    public function testAscFixture()
    {
        $this->assertMappedClasses();

        parent::testAscFixture();
    }

    public function testDescFixture()
    {
        $this->assertMappedClasses();

        parent::testDescFixture();
    }

    public function testVersionFixture()
    {
        $this->assertMappedClasses();

        parent::testVersionFixture();
    }

    public function testXmlFixture()
    {
        $this->assertMappedClasses();

        parent::testXmlFixture();
    }

    public function testXmlValueFixture()
    {
        $this->assertMappedClasses();

        parent::testXmlValueFixture();
    }

    public function testUnknownFixture()
    {
        $this->assertMappedClasses();

        parent::testUnknownFixture();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFileNotFound()
    {
        $this->createLoader('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFileNotReadable()
    {
        $this->createLoader('lock');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMalformed()
    {
        $this->loader = $this->createLoader('malformed');
        $this->loadClassMetadata(new ClassMetadata(\stdClass::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExclusionPolicy()
    {
        $this->loader = $this->createLoader('exclusion_policy');
        $this->loadClassMetadata(new ClassMetadata(ExcludeFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExclude()
    {
        $this->loader = $this->createLoader('exclude');
        $this->loadClassMetadata(new ClassMetadata(ExcludeFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExpose()
    {
        $this->loader = $this->createLoader('expose');
        $this->loadClassMetadata(new ClassMetadata(ExposeFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testReadable()
    {
        $this->loader = $this->createLoader('readable');
        $this->loadClassMetadata(new ClassMetadata(ReadableFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testReadableClass()
    {
        $this->loader = $this->createLoader('readable_class');
        $this->loadClassMetadata(new ClassMetadata(ReadableClassFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWritable()
    {
        $this->loader = $this->createLoader('writable');
        $this->loadClassMetadata(new ClassMetadata(WritableFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWritableClass()
    {
        $this->loader = $this->createLoader('writable_class');
        $this->loadClassMetadata(new ClassMetadata(WritableClassFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAccessor()
    {
        $this->loader = $this->createLoader('accessor');
        $this->loadClassMetadata(new ClassMetadata(AccessorFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMutator()
    {
        $this->loader = $this->createLoader('mutator');
        $this->loadClassMetadata(new ClassMetadata(MutatorFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOrder()
    {
        $this->loader = $this->createLoader('order');
        $this->loadClassMetadata(new ClassMetadata(OrderFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOrderEmpty()
    {
        $this->loader = $this->createLoader('order_empty');
        $this->loadClassMetadata(new ClassMetadata(OrderFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOrderEmptyProperty()
    {
        $this->loader = $this->createLoader('order_empty_property');
        $this->loadClassMetadata(new ClassMetadata(OrderFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testProperties()
    {
        $this->loader = $this->createLoader('properties');
        $this->loadClassMetadata(new ClassMetadata(\stdClass::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertyAlias()
    {
        $this->loader = $this->createLoader('alias');
        $this->loadClassMetadata(new ClassMetadata(ScalarFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertyType()
    {
        $this->loader = $this->createLoader('type');
        $this->loadClassMetadata(new ClassMetadata(ScalarFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertySince()
    {
        $this->loader = $this->createLoader('since');
        $this->loadClassMetadata(new ClassMetadata(VersionFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertyUntil()
    {
        $this->loader = $this->createLoader('until');
        $this->loadClassMetadata(new ClassMetadata(VersionFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertyMaxDepth()
    {
        $this->loader = $this->createLoader('max_depth');
        $this->loadClassMetadata(new ClassMetadata(MaxDepthFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertyGroups()
    {
        $this->loader = $this->createLoader('groups');
        $this->loadClassMetadata(new ClassMetadata(GroupFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlRoot()
    {
        $this->loader = $this->createLoader('xml_root');
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlAttribute()
    {
        $this->loader = $this->createLoader('xml_attribute');
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlEntry()
    {
        $this->loader = $this->createLoader('xml_entry');
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlEntryAttribute()
    {
        $this->loader = $this->createLoader('xml_entry_attribute');
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlKeyAsAttribute()
    {
        $this->loader = $this->createLoader('xml_key_as_attribute');
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlKeyAsNode()
    {
        $this->loader = $this->createLoader('xml_key_as_node');
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlInline()
    {
        $this->loader = $this->createLoader('xml_inline');
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlValue()
    {
        $this->loader = $this->createLoader('xml_value');
        $this->loadClassMetadata(new ClassMetadata(XmlValueFixture::class));
    }

    protected function assertMappedClasses()
    {
        $this->assertSame([
            ArrayFixture::class,
            ScalarFixture::class,
            DateTimeFixture::class,
            ExcludeFixture::class,
            ExposeFixture::class,
            AccessorFixture::class,
            MutatorFixture::class,
            MaxDepthFixture::class,
            GroupFixture::class,
            OrderFixture::class,
            AscFixture::class,
            DescFixture::class,
            ReadableFixture::class,
            ReadableClassFixture::class,
            WritableFixture::class,
            WritableClassFixture::class,
            VersionFixture::class,
            XmlFixture::class,
            XmlValueFixture::class,
        ], $this->loader->getMappedClasses());
    }
}
