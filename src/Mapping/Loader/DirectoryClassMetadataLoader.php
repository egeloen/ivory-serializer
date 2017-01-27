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
class DirectoryClassMetadataLoader implements ClassMetadataLoaderInterface
{
    const EXTENSION_JSON = 'json';
    const EXTENSION_XML = 'xml';
    const EXTENSION_YAML = 'yml';

    /**
     * @var string[]
     */
    private $directories;

    /**
     * @var TypeParserInterface
     */
    private $typeParser;

    /**
     * @var ClassMetadataLoaderInterface|null
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
        if (!$this->initialized) {
            $loaders = [];

            foreach ($this->directories as $directory) {
                $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory));

                foreach ($iterator as $file) {
                    if ($file->isDir()) {
                        continue;
                    }

                    switch ($file->getExtension()) {
                        case self::EXTENSION_JSON:
                            $loaders[] = new JsonClassMetadataLoader($file->getRealPath(), $this->typeParser);
                            break;

                        case self::EXTENSION_XML:
                            $loaders[] = new XmlClassMetadataLoader($file->getRealPath(), $this->typeParser);
                            break;

                        case self::EXTENSION_YAML:
                            $loaders[] = new YamlClassMetadataLoader($file->getRealPath(), $this->typeParser);
                            break;
                    }
                }
            }

            if (!empty($loaders)) {
                $this->loader = count($loaders) > 1 ? new ChainClassMetadataLoader($loaders) : array_shift($loaders);
            }

            $this->initialized = true;
        }

        return $this->loader !== null && $this->loader->loadClassMetadata($classMetadata);
    }
}
