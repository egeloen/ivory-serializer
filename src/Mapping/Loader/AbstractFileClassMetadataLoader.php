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

use Ivory\Serializer\Type\Parser\TypeParserInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractFileClassMetadataLoader extends AbstractClassMetadataLoader implements MappedClassMetadataLoaderInterface
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var mixed[]
     */
    private $data;

    /**
     * @param string                   $file
     * @param TypeParserInterface|null $typeParser
     */
    public function __construct($file, TypeParserInterface $typeParser = null)
    {
        parent::__construct($typeParser);

        if (!is_file($file)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not exist.', $file));
        }

        if (!is_readable($file)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" is not readable.', $file));
        }

        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    public function getMappedClasses()
    {
        if ($this->data === null) {
            $this->data = $this->loadFile($this->file);
        }

        return array_keys($this->data);
    }

    /**
     * @param string $file
     *
     * @return mixed[]
     */
    abstract protected function loadFile($file);

    /**
     * {@inheritdoc}
     */
    protected function loadData($class)
    {
        if ($this->data === null) {
            $this->data = $this->loadFile($this->file);
        }

        if (isset($this->data[$class])) {
            return $this->data[$class];
        }
    }
}
