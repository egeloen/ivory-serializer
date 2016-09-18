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

use Ivory\Serializer\Mapping\Loader\ReflectionClassMetadataLoader;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class TypeRefectionClassMetadataLoaderTest extends AbstractReflectionClassMetadataLoaderTest
{
    /**
     * {@inheritdoc}
     */
    protected function createLoader($file)
    {
        return new ReflectionClassMetadataLoader(new PropertyInfoExtractor(
            [$reflectionExtractor = new ReflectionExtractor()],
            [$phpDocExtractor = new PhpDocExtractor(), $reflectionExtractor],
            [$phpDocExtractor],
            [$reflectionExtractor]
        ));
    }
}
