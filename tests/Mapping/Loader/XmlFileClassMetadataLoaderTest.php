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

use Ivory\Serializer\Mapping\Loader\FileClassMetadataLoader;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class XmlFileClassMetadataLoaderTest extends AbstractFileClassMetadataLoaderTest
{
    /**
     * {@inheritdoc}
     */
    protected function createLoader($file)
    {
        return new FileClassMetadataLoader(__DIR__.'/../../Fixture/config/xml/'.$file.'/'.$file.'.xml');
    }

    public function testExclude()
    {
    }

    public function testExpose()
    {
    }

    public function testReadable()
    {
    }

    public function testReadableClass()
    {
    }

    public function testWritable()
    {
    }

    public function testWritableClass()
    {
    }

    public function testXmlAttribute()
    {
    }

    public function testXmlValue()
    {
    }

    public function testXmlInline()
    {
    }

    public function testXmlKeyAsAttribute()
    {
    }

    public function testXmlKeyAsNode()
    {
    }
}
