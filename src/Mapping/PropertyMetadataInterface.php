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
interface PropertyMetadataInterface extends MetadataInterface
{
    /**
     * @return string
     */
    public function getClass();

    /**
     * @param string $class
     */
    public function setClass($class);

    /**
     * @return bool
     */
    public function hasAlias();

    /**
     * @return string|null
     */
    public function getAlias();

    /**
     * @param string|null $alias
     */
    public function setAlias($alias);

    /**
     * @return bool
     */
    public function hasType();

    /**
     * @return TypeMetadataInterface|null
     */
    public function getType();

    /**
     * @param TypeMetadataInterface|null $type
     */
    public function setType(TypeMetadataInterface $type = null);

    /**
     * @return bool
     */
    public function hasReadable();

    /**
     * @return bool
     */
    public function isReadable();

    /**
     * @param bool $readable
     */
    public function setReadable($readable);

    /**
     * @return bool
     */
    public function hasWritable();

    /**
     * @return bool
     */
    public function isWritable();

    /**
     * @param bool $writable
     */
    public function setWritable($writable);

    /**
     * @return bool
     */
    public function hasAccessor();

    /**
     * @return string|null
     */
    public function getAccessor();

    /**
     * @param string|null $accessor
     */
    public function setAccessor($accessor);

    /**
     * @return bool
     */
    public function hasMutator();

    /**
     * @return string|null
     */
    public function getMutator();

    /**
     * @param string|null $mutator
     */
    public function setMutator($mutator);

    /**
     * @return bool
     */
    public function hasSinceVersion();

    /**
     * @return string|null
     */
    public function getSinceVersion();

    /**
     * @param string|null $since
     */
    public function setSinceVersion($since);

    /**
     * @return bool
     */
    public function hasUntilVersion();

    /**
     * @return string|null
     */
    public function getUntilVersion();

    /**
     * @param string|null $until
     */
    public function setUntilVersion($until);

    /**
     * @return bool
     */
    public function hasMaxDepth();

    /**
     * @return int|null
     */
    public function getMaxDepth();

    /**
     * @param int|null $maxDepth
     */
    public function setMaxDepth($maxDepth);

    /**
     * @return bool
     */
    public function hasGroups();

    /**
     * @return string[]
     */
    public function getGroups();

    /**
     * @param string[] $groups
     */
    public function setGroups(array $groups);

    /**
     * @param string[] $groups
     */
    public function addGroups(array $groups);

    /**
     * @param string $group
     *
     * @return bool
     */
    public function hasGroup($group);

    /**
     * @param string $group
     */
    public function addGroup($group);

    /**
     * @param string $group
     */
    public function removeGroup($group);

    /**
     * @return bool
     */
    public function hasXmlAttribute();

    /**
     * @return bool
     */
    public function isXmlAttribute();

    /**
     * @param bool $xmlAttribute
     */
    public function setXmlAttribute($xmlAttribute);

    /**
     * @return bool
     */
    public function hasXmlValue();

    /**
     * @return bool
     */
    public function isXmlValue();

    /**
     * @param bool $xmlValue
     */
    public function setXmlValue($xmlValue);

    /**
     * @return bool
     */
    public function hasXmlInline();

    /**
     * @return bool
     */
    public function isXmlInline();

    /**
     * @param bool $xmlInline
     */
    public function setXmlInline($xmlInline);

    /**
     * @return bool
     */
    public function hasXmlEntry();

    /**
     * @return string|null
     */
    public function getXmlEntry();

    /**
     * @param string $xmlEntry
     */
    public function setXmlEntry($xmlEntry);

    /**
     * @return bool
     */
    public function hasXmlEntryAttribute();

    /**
     * @return string|null
     */
    public function getXmlEntryAttribute();

    /**
     * @param string $xmlEntryAttribute
     */
    public function setXmlEntryAttribute($xmlEntryAttribute);

    /**
     * @return bool
     */
    public function hasXmlKeyAsAttribute();

    /**
     * @return bool
     */
    public function useXmlKeyAsAttribute();

    /**
     * @param bool $xmlKeyAsAttribute
     */
    public function setXmlKeyAsAttribute($xmlKeyAsAttribute);

    /**
     * @return bool
     */
    public function hasXmlKeyAsNode();

    /**
     * @return bool
     */
    public function useXmlKeyAsNode();

    /**
     * @param bool $xmlKeyAsNode
     */
    public function setXmlKeyAsNode($xmlKeyAsNode);

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     */
    public function merge(PropertyMetadataInterface $propertyMetadata);
}
