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
use Ivory\Serializer\Mapping\Loader\JsonClassMetadataLoader;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class JsonClassMetadataLoaderTest extends AbstractFileClassMetadataLoaderTest
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMissingContent()
    {
        $this->loader = $this->createLoader('invalid');
        $this->loadClassMetadata(new ClassMetadata(\stdClass::class));
    }

    /**
     * {@inheritdoc}
     */
    protected function createLoader($file)
    {
        return new JsonClassMetadataLoader(__DIR__.'/../../Fixture/config/json/'.$file.'/'.$file.'.json');
    }
}
