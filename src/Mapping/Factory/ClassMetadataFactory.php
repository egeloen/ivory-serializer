<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use Ivory\Serializer\Mapping\ClassMetadata;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\Loader\AnnotationClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\ChainClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\ClassMetadataLoaderInterface;
use Ivory\Serializer\Mapping\Loader\ReflectionClassMetadataLoader;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ClassMetadataFactory implements ClassMetadataFactoryInterface
{
    /**
     * @var ClassMetadataLoaderInterface
     */
    private $loader;

    /**
     * @var ClassMetadataInterface[]
     */
    private $classMetadatas = [];

    /**
     * @param ClassMetadataLoaderInterface $loader
     */
    public function __construct(ClassMetadataLoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param ClassMetadataLoaderInterface[] $loaders
     *
     * @return ClassMetadataFactoryInterface
     */
    public static function create(array $loaders = [])
    {
        if (empty($loaders)) {
            $loaders[] = class_exists(AnnotationReader::class)
                ? new AnnotationClassMetadataLoader(new AnnotationReader())
                : new ReflectionClassMetadataLoader();
        }

        return new static(count($loaders) > 1 ? new ChainClassMetadataLoader($loaders) : array_shift($loaders));
    }

    /**
     * {@inheritdoc}
     */
    public function getClassMetadata($class)
    {
        if (array_key_exists($class, $this->classMetadatas)) {
            return $this->classMetadatas[$class];
        }

        $classMetadata = new ClassMetadata($class);
        $found = false;

        foreach (array_reverse(class_parents($class)) as $parent) {
            if (($parentMetadata = $this->getClassMetadata($parent)) !== null) {
                $classMetadata->merge($parentMetadata);
                $found = true;
            }
        }

        $found = $this->loader->loadClassMetadata($classMetadata) || $found;

        return $this->classMetadatas[$class] = $found ? $classMetadata : null;
    }
}
