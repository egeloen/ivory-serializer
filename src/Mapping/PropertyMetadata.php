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
class PropertyMetadata implements PropertyMetadataInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var TypeMetadataInterface|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $since;

    /**
     * @var string|null
     */
    private $until;

    /**
     * @var int|null
     */
    private $maxDepth;

    /**
     * @var string[]
     */
    private $groups = [];

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
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAlias()
    {
        return $this->alias !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * {@inheritdoc}
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritdoc}
     */
    public function hasType()
    {
        return $this->type !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(TypeMetadataInterface $type = null)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function hasSinceVersion()
    {
        return $this->since !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSinceVersion()
    {
        return $this->since;
    }

    /**
     * {@inheritdoc}
     */
    public function setSinceVersion($since)
    {
        $this->since = $since;
    }

    /**
     * {@inheritdoc}
     */
    public function hasUntilVersion()
    {
        return $this->until !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUntilVersion()
    {
        return $this->until;
    }

    /**
     * {@inheritdoc}
     */
    public function setUntilVersion($until)
    {
        $this->until = $until;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMaxDepth()
    {
        return $this->maxDepth !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxDepth($maxDepth)
    {
        $this->maxDepth = $maxDepth;
    }

    /**
     * {@inheritdoc}
     */
    public function hasGroups()
    {
        return !empty($this->groups);
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        return array_values($this->groups);
    }

    /**
     * {@inheritdoc}
     */
    public function setGroups(array $groups)
    {
        $this->groups = [];
        $this->addGroups($groups);
    }

    /**
     * {@inheritdoc}
     */
    public function addGroups(array $groups)
    {
        foreach ($groups as $group) {
            $this->addGroup($group);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasGroup($group)
    {
        return in_array($group, $this->groups, true);
    }

    /**
     * {@inheritdoc}
     */
    public function addGroup($group)
    {
        if (!$this->hasGroup($group)) {
            $this->groups[] = $group;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeGroup($group)
    {
        unset($this->groups[array_search($group, $this->groups, true)]);
    }

    /**
     * {@inheritdoc}
     */
    public function merge(PropertyMetadataInterface $propertyMetadata)
    {
        if (!$this->hasAlias() && $propertyMetadata->hasAlias()) {
            $this->setAlias($propertyMetadata->getAlias());
        }

        if (!$this->hasType() && $propertyMetadata->hasType()) {
            $this->setType($propertyMetadata->getType());
        }

        if (!$this->hasSinceVersion() && $propertyMetadata->hasSinceVersion()) {
            $this->setSinceVersion($propertyMetadata->getSinceVersion());
        }

        if (!$this->hasUntilVersion() && $propertyMetadata->hasUntilVersion()) {
            $this->setUntilVersion($propertyMetadata->getUntilVersion());
        }

        if (!$this->hasMaxDepth() && $propertyMetadata->hasMaxDepth()) {
            $this->setMaxDepth($propertyMetadata->getMaxDepth());
        }

        foreach ($propertyMetadata->getGroups() as $group) {
            $this->addGroup($group);
        }
    }
}
