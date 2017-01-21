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
use phpDocumentor\Reflection\ClassReflector;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ClassMetadataFactory extends AbstractClassMetadataFactory
{
    /**
     * @var ClassMetadataLoaderInterface
     */
    private $loader;

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
            $extractor = null;

            if (class_exists(PropertyInfoExtractor::class)) {
                $extractors = $typeExtractors = [new ReflectionExtractor()];

                if (class_exists(ClassReflector::class)) {
                    array_unshift($typeExtractors, new PhpDocExtractor());
                }

                $extractor = new PropertyInfoExtractor($extractors, $typeExtractors, [], $extractors);
            }

            $loaders = [new ReflectionClassMetadataLoader($extractor)];

            if (class_exists(AnnotationReader::class)) {
                $loaders[] = new AnnotationClassMetadataLoader(new AnnotationReader());
            }
        }

        return new static(count($loaders) > 1 ? new ChainClassMetadataLoader($loaders) : array_shift($loaders));
    }

    /**
     * {@inheritdoc}
     */
    protected function fetchClassMetadata($class)
    {
        $classMetadata = new ClassMetadata($class);
        $found = false;

        if (($parentMetadata = $this->getParentClassMetadata($class)) !== null) {
            $classMetadata->merge($parentMetadata);
            $found = true;
        }

        $found = $this->loader->loadClassMetadata($classMetadata) || $found;

        return $found ? $classMetadata : null;
    }

    /**
     * @param string $class
     *
     * @return ClassMetadataInterface|null
     */
    private function getParentClassMetadata($class)
    {
        if (($parent = get_parent_class($class)) !== false) {
            return $this->getClassMetadata($parent);
        }
    }
}
