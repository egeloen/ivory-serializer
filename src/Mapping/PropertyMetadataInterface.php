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
     * @param PropertyMetadataInterface $propertyMetadata
     */
    public function merge(PropertyMetadataInterface $propertyMetadata);
}
