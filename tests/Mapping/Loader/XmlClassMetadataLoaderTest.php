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
use Ivory\Serializer\Mapping\Loader\XmlClassMetadataLoader;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class XmlClassMetadataLoaderTest extends AbstractFileClassMetadataLoaderTest
{
    /**
     * {@inheritdoc}
     */
    protected function createLoader($file)
    {
        return new XmlClassMetadataLoader(__DIR__.'/../../Fixture/config/xml/'.$file.'.xml');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingContent()
    {
        $this->setLoader($this->createLoader('content_missing'));
        $this->loadClassMetadata(new ClassMetadata(\stdClass::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDoctype()
    {
        $this->setLoader($this->createLoader('doctype'));
        $this->loadClassMetadata(new ClassMetadata(\stdClass::class));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testXsd()
    {
        $this->setLoader($this->createLoader('xsd'));
        $this->loadClassMetadata(new ClassMetadata(\stdClass::class));
    }
}
