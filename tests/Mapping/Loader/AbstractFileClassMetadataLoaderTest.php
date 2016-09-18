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
use Ivory\Tests\Serializer\Fixture\GroupFixture;
use Ivory\Tests\Serializer\Fixture\MaxDepthFixture;
use Ivory\Tests\Serializer\Fixture\ScalarFixture;
use Ivory\Tests\Serializer\Fixture\VersionFixture;

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
    public function testProperties()
    {
        $this->setLoader($this->createLoader('properties'));
        $this->loadClassMetadata(new ClassMetadata(\stdClass::class));
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
}
