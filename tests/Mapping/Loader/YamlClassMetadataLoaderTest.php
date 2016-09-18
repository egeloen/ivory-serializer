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

use Ivory\Serializer\Mapping\Loader\YamlClassMetadataLoader;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class YamlClassMetadataLoaderTest extends AbstractFileClassMetadataLoaderTest
{
    /**
     * {@inheritdoc}
     */
    protected function createLoader($file)
    {
        return new YamlClassMetadataLoader(__DIR__.'/../../Fixture/config/yaml/'.$file.'.yml');
    }
}
