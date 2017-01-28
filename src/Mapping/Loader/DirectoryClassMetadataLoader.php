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
use Ivory\Serializer\Type\Parser\TypeParser;
use Ivory\Serializer\Type\Parser\TypeParserInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class DirectoryClassMetadataLoader implements MappedClassMetadataLoaderInterface
{
    /**
     * @var string[]
     */
    private $directories;

    /**
     * @var TypeParserInterface
     */
    private $typeParser;

    /**
     * @var MappedClassMetadataLoaderInterface|null
     */
    private $loader;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @param string|string[]          $directories
     * @param TypeParserInterface|null $typeParser
     */
    public function __construct($directories, TypeParserInterface $typeParser = null)
    {
        $directories = is_array($directories) ? $directories : [$directories];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                throw new \InvalidArgumentException(sprintf('The directory "%s" does not exist.', $directory));
            }

            if (!is_readable($directory)) {
                throw new \InvalidArgumentException(sprintf('The directory "%s" is not readable.', $directory));
            }
        }

        $this->directories = $directories;
        $this->typeParser = $typeParser ?: new TypeParser();
    }

    /**
     * {@inheritdoc}
     */
    public function loadClassMetadata(ClassMetadataInterface $classMetadata)
    {
        return ($loader = $this->getLoader()) !== null && $loader->loadClassMetadata($classMetadata);
    }

    /**
     * {@inheritdoc}
     */
    public function getMappedClasses()
    {
        return ($loader = $this->getLoader()) !== null ? $loader->getMappedClasses() : [];
    }

    /**
     * @return MappedClassMetadataLoaderInterface|null
     */
    private function getLoader()
    {
        if (!$this->initialized) {
            $this->createLoader();
            $this->initialized = true;
        }

        return $this->loader;
    }

    private function createLoader()
    {
        $extensions = [
            FileClassMetadataLoader::EXTENSION_JSON,
            FileClassMetadataLoader::EXTENSION_XML,
            FileClassMetadataLoader::EXTENSION_YAML,
        ];

        $loaders = [];

        foreach ($this->directories as $directory) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));

            foreach ($iterator as $file) {
                if ($file->isDir()) {
                    continue;
                }

                $path = $file->getRealPath();

                if (!in_array(pathinfo($path, PATHINFO_EXTENSION), $extensions, true)) {
                    continue;
                }

                $loaders[] = new FileClassMetadataLoader($path, $this->typeParser);
            }
        }

        if (!empty($loaders)) {
            $this->loader = count($loaders) > 1 ? new ChainClassMetadataLoader($loaders) : array_shift($loaders);
        }
    }
}
