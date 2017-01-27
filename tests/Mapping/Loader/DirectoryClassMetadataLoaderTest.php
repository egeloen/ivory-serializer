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

use Ivory\Serializer\Mapping\Loader\DirectoryClassMetadataLoader;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class DirectoryClassMetadataLoaderTest extends AbstractFileClassMetadataLoaderTest
{
    /**
     * {@inheritdoc}
     */
    protected function createLoader($file)
    {
        $directories = [];

        foreach (['json', 'xml', 'yaml'] as $format) {
            $directories[] = __DIR__.'/../../Fixture/config/'.$format.'/'.$file;
        }

        return new DirectoryClassMetadataLoader($directories);
    }
}
