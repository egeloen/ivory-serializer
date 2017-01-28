<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping\Loader;

use Ivory\Serializer\Mapping\ClassMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ChainClassMetadataLoader implements MappedClassMetadataLoaderInterface
{
    /**
     * @var ClassMetadataLoaderInterface[]
     */
    private $loaders;

    /**
     * @param ClassMetadataLoaderInterface[] $loaders
     */
    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
    }

    /**
     * {@inheritdoc}
     */
    public function loadClassMetadata(ClassMetadataInterface $classMetadata)
    {
        $result = false;

        foreach ($this->loaders as $loader) {
            $result = $loader->loadClassMetadata($classMetadata) || $result;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getMappedClasses()
    {
        $classes = [];

        foreach ($this->loaders as $loader) {
            if ($loader instanceof MappedClassMetadataLoaderInterface) {
                $classes = array_merge($classes, $loader->getMappedClasses());
            }
        }

        return array_unique($classes);
    }
}
