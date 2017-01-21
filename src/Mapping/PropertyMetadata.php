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
    private $class;

    /**
     * @var string
     */
    private $alias;

    /**
     * @var TypeMetadataInterface|null
     */
    private $type;

    /**
     * @var bool
     */
    private $readable = true;

    /**
     * @var bool
     */
    private $writable = true;

    /**
     * @var string|null
     */
    private $accessor;

    /**
     * @var string|null
     */
    private $mutator;

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
     * @param string $class
     */
    public function __construct($name, $class)
    {
        $this->setName($name);
        $this->setClass($class);
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
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function setClass($class)
    {
        $this->class = $class;
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
    public function isReadable()
    {
        return $this->readable;
    }

    /**
     * {@inheritdoc}
     */
    public function setReadable($readable)
    {
        $this->readable = $readable;
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable()
    {
        return $this->writable;
    }

    /**
     * {@inheritdoc}
     */
    public function setWritable($writable)
    {
        $this->writable = $writable;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAccessor()
    {
        return $this->accessor !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessor()
    {
        return $this->accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessor($accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMutator()
    {
        return $this->mutator !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getMutator()
    {
        return $this->mutator;
    }

    /**
     * {@inheritdoc}
     */
    public function setMutator($mutator)
    {
        $this->mutator = $mutator;
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
        return array_keys($this->groups);
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
        return isset($this->groups[$group]);
    }

    /**
     * {@inheritdoc}
     */
    public function addGroup($group)
    {
        if (!$this->hasGroup($group)) {
            $this->groups[$group] = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeGroup($group)
    {
        unset($this->groups[$group]);
    }

    /**
     * {@inheritdoc}
     */
    public function merge(PropertyMetadataInterface $propertyMetadata)
    {
        $this->setReadable($propertyMetadata->isReadable());
        $this->setWritable($propertyMetadata->isWritable());

        if ($propertyMetadata->hasAlias()) {
            $this->setAlias($propertyMetadata->getAlias());
        }

        if ($propertyMetadata->hasType()) {
            $this->setType($propertyMetadata->getType());
        }

        if ($propertyMetadata->hasAccessor()) {
            $this->setAccessor($propertyMetadata->getAccessor());
        }

        if ($propertyMetadata->hasMutator()) {
            $this->setMutator($propertyMetadata->getMutator());
        }

        if ($propertyMetadata->hasSinceVersion()) {
            $this->setSinceVersion($propertyMetadata->getSinceVersion());
        }

        if ($propertyMetadata->hasUntilVersion()) {
            $this->setUntilVersion($propertyMetadata->getUntilVersion());
        }

        if ($propertyMetadata->hasMaxDepth()) {
            $this->setMaxDepth($propertyMetadata->getMaxDepth());
        }

        foreach ($propertyMetadata->getGroups() as $group) {
            $this->addGroup($group);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->name,
            $this->class,
            $this->alias,
            $this->type,
            $this->readable,
            $this->writable,
            $this->accessor,
            $this->mutator,
            $this->since,
            $this->until,
            $this->maxDepth,
            $this->groups,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->name,
            $this->class,
            $this->alias,
            $this->type,
            $this->readable,
            $this->writable,
            $this->accessor,
            $this->mutator,
            $this->since,
            $this->until,
            $this->maxDepth,
            $this->groups
        ) = unserialize($serialized);
    }
}
