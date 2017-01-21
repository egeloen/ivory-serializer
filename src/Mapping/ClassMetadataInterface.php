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
interface ClassMetadataInterface extends MetadataInterface
{
    /**
     * @return bool
     */
    public function hasProperties();

    /**
     * @return PropertyMetadataInterface[]
     */
    public function getProperties();

    /**
     * @param PropertyMetadataInterface[] $properties
     */
    public function setProperties(array $properties);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasProperty($name);

    /**
     * @param string $name
     *
     * @return PropertyMetadataInterface|null
     */
    public function getProperty($name);

    /**
     * @param PropertyMetadataInterface $property
     */
    public function addProperty(PropertyMetadataInterface $property);

    /**
     * @param string $name
     */
    public function removeProperty($name);

    /**
     * @return bool
     */
    public function hasXmlRoot();

    /**
     * @return string|null
     */
    public function getXmlRoot();

    /**
     * @param string|null $xmlRoot
     */
    public function setXmlRoot($xmlRoot);

    /**
     * @param ClassMetadataInterface $classMetadata
     */
    public function merge(ClassMetadataInterface $classMetadata);
}
