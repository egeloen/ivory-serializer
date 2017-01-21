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
use Ivory\Tests\Serializer\Fixture\AccessorFixture;
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
        $this->setLoader($this->createLoader('malformed'));
        $this->loadClassMetadata(new ClassMetadata(\stdClass::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExclusionPolicy()
    {
        $this->setLoader($this->createLoader('exclusion_policy'));
        $this->loadClassMetadata(new ClassMetadata(ExcludeFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExclude()
    {
        $this->setLoader($this->createLoader('exclude'));
        $this->loadClassMetadata(new ClassMetadata(ExcludeFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExpose()
    {
        $this->setLoader($this->createLoader('expose'));
        $this->loadClassMetadata(new ClassMetadata(ExposeFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testReadable()
    {
        $this->setLoader($this->createLoader('readable'));
        $this->loadClassMetadata(new ClassMetadata(ReadableFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testReadableClass()
    {
        $this->setLoader($this->createLoader('readable_class'));
        $this->loadClassMetadata(new ClassMetadata(ReadableClassFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWritable()
    {
        $this->setLoader($this->createLoader('writable'));
        $this->loadClassMetadata(new ClassMetadata(WritableFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWritableClass()
    {
        $this->setLoader($this->createLoader('writable_class'));
        $this->loadClassMetadata(new ClassMetadata(WritableClassFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAccessor()
    {
        $this->setLoader($this->createLoader('accessor'));
        $this->loadClassMetadata(new ClassMetadata(AccessorFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMutator()
    {
        $this->setLoader($this->createLoader('mutator'));
        $this->loadClassMetadata(new ClassMetadata(MutatorFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOrder()
    {
        $this->setLoader($this->createLoader('order'));
        $this->loadClassMetadata(new ClassMetadata(OrderFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOrderEmpty()
    {
        $this->setLoader($this->createLoader('order_empty'));
        $this->loadClassMetadata(new ClassMetadata(OrderFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOrderEmptyProperty()
    {
        $this->setLoader($this->createLoader('order_empty_property'));
        $this->loadClassMetadata(new ClassMetadata(OrderFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testProperties()
    {
        $this->setLoader($this->createLoader('properties'));
        $this->loadClassMetadata(new ClassMetadata(\stdClass::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertyAlias()
    {
        $this->setLoader($this->createLoader('alias'));
        $this->loadClassMetadata(new ClassMetadata(ScalarFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertyType()
    {
        $this->setLoader($this->createLoader('type'));
        $this->loadClassMetadata(new ClassMetadata(ScalarFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertySince()
    {
        $this->setLoader($this->createLoader('since'));
        $this->loadClassMetadata(new ClassMetadata(VersionFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertyUntil()
    {
        $this->setLoader($this->createLoader('until'));
        $this->loadClassMetadata(new ClassMetadata(VersionFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertyMaxDepth()
    {
        $this->setLoader($this->createLoader('max_depth'));
        $this->loadClassMetadata(new ClassMetadata(MaxDepthFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPropertyGroups()
    {
        $this->setLoader($this->createLoader('groups'));
        $this->loadClassMetadata(new ClassMetadata(GroupFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlRoot()
    {
        $this->setLoader($this->createLoader('xml_root'));
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlAttribute()
    {
        $this->setLoader($this->createLoader('xml_attribute'));
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlEntry()
    {
        $this->setLoader($this->createLoader('xml_entry'));
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlEntryAttribute()
    {
        $this->setLoader($this->createLoader('xml_entry_attribute'));
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlKeyAsAttribute()
    {
        $this->setLoader($this->createLoader('xml_key_as_attribute'));
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlKeyAsNode()
    {
        $this->setLoader($this->createLoader('xml_key_as_node'));
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlInline()
    {
        $this->setLoader($this->createLoader('xml_inline'));
        $this->loadClassMetadata(new ClassMetadata(XmlFixture::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXmlValue()
    {
        $this->setLoader($this->createLoader('xml_value'));
        $this->loadClassMetadata(new ClassMetadata(XmlValueFixture::class));
    }
}
