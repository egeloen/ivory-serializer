<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ClassMetadata implements ClassMetadataInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var PropertyMetadataInterface[]
     */
    private $properties = [];

    /**
     * @var string|null
     */
    private $xmlRoot;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        if (!class_exists($name)) {
            throw new \InvalidArgumentException(sprintf('The class "%s" does not exist.', $name));
        }

        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function hasProperties()
    {
        return !empty($this->properties);
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperties(array $properties)
    {
        $this->properties = [];

        foreach ($properties as $property) {
            $this->addProperty($property);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasProperty($name)
    {
        return isset($this->properties[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty($name)
    {
        return $this->hasProperty($name) ? $this->properties[$name] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function addProperty(PropertyMetadataInterface $property)
    {
        $name = $property->getName();

        if ($this->hasProperty($name)) {
            $this->getProperty($name)->merge($property);
        } else {
            $this->properties[$name] = $property;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeProperty($name)
    {
        unset($this->properties[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasXmlRoot()
    {
        return $this->xmlRoot !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getXmlRoot()
    {
        return $this->xmlRoot;
    }

    /**
     * {@inheritdoc}
     */
    public function setXmlRoot($xmlRoot)
    {
        $this->xmlRoot = $xmlRoot;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(ClassMetadataInterface $classMetadata)
    {
        if ($classMetadata->hasXmlRoot()) {
            $this->setXmlRoot($classMetadata->getXmlRoot());
        }

        foreach ($classMetadata->getProperties() as $property) {
            $this->addProperty($property);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->name,
            $this->properties,
            $this->xmlRoot,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->name,
            $this->properties,
            $this->xmlRoot
        ) = unserialize($serialized);
    }
}
