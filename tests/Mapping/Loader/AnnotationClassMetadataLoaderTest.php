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

use Doctrine\Common\Annotations\AnnotationReader;
use Ivory\Serializer\Mapping\Loader\AnnotationClassMetadataLoader;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class AnnotationClassMetadataLoaderTest extends AbstractClassMetadataLoaderTest
{
    /**
     * {@inheritdoc}
     */
    protected function createLoader($file)
    {
        return new AnnotationClassMetadataLoader(new AnnotationReader());
    }
}
