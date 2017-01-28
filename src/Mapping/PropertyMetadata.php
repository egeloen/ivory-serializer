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
     * @var bool|null
     */
    private $readable;

    /**
     * @var bool|null
     */
    private $writable;

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
     * @var bool|null
     */
    private $xmlAttribute;

    /**
     * @var bool|null
     */
    private $xmlValue;

    /**
     * @var bool|null
     */
    private $xmlInline;

    /**
     * @var string|null
     */
    private $xmlEntry;

    /**
     * @var string|null
     */
    private $xmlEntryAttribute;

    /**
     * @var bool|null
     */
    private $xmlKeyAsAttribute;

    /**
     * @var bool|null
     */
    private $xmlKeyAsNode;

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
    public function hasReadable()
    {
        return $this->readable !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable()
    {
        return !$this->hasReadable() || $this->readable;
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
    public function hasWritable()
    {
        return $this->writable !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable()
    {
        return !$this->hasWritable() || $this->writable;
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
    public function hasXmlAttribute()
    {
        return $this->xmlAttribute !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function isXmlAttribute()
    {
        return $this->hasXmlAttribute() && $this->xmlAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function setXmlAttribute($xmlAttribute)
    {
        $this->xmlAttribute = $xmlAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function hasXmlValue()
    {
        return $this->xmlValue !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function isXmlValue()
    {
        return $this->hasXmlValue() && $this->xmlValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setXmlValue($xmlValue)
    {
        $this->xmlValue = $xmlValue;
    }

    /**
     * {@inheritdoc}
     */
    public function hasXmlInline()
    {
        return $this->xmlInline !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function isXmlInline()
    {
        return $this->hasXmlInline() && $this->xmlInline;
    }

    /**
     * {@inheritdoc}
     */
    public function setXmlInline($xmlInline)
    {
        $this->xmlInline = $xmlInline;
    }

    /**
     * {@inheritdoc}
     */
    public function hasXmlEntry()
    {
        return $this->xmlEntry !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getXmlEntry()
    {
        return $this->xmlEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function setXmlEntry($xmlEntry)
    {
        $this->xmlEntry = $xmlEntry;
    }

    /**
     * {@inheritdoc}
     */
    public function hasXmlEntryAttribute()
    {
        return $this->xmlEntryAttribute !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getXmlEntryAttribute()
    {
        return $this->xmlEntryAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function setXmlEntryAttribute($xmlEntryAttribute)
    {
        $this->xmlEntryAttribute = $xmlEntryAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function hasXmlKeyAsAttribute()
    {
        return $this->xmlKeyAsAttribute !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function useXmlKeyAsAttribute()
    {
        return $this->xmlKeyAsAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function setXmlKeyAsAttribute($xmlKeyAsAttribute)
    {
        $this->xmlKeyAsAttribute = $xmlKeyAsAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function hasXmlKeyAsNode()
    {
        return $this->xmlKeyAsNode !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function useXmlKeyAsNode()
    {
        return $this->xmlKeyAsNode;
    }

    /**
     * {@inheritdoc}
     */
    public function setXmlKeyAsNode($xmlKeyAsNode)
    {
        $this->xmlKeyAsNode = $xmlKeyAsNode;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(PropertyMetadataInterface $propertyMetadata)
    {
        if ($propertyMetadata->hasAlias()) {
            $this->setAlias($propertyMetadata->getAlias());
        }

        if ($propertyMetadata->hasType()) {
            $this->setType($propertyMetadata->getType());
        }

        if ($propertyMetadata->hasReadable()) {
            $this->setReadable($propertyMetadata->isReadable());
        }

        if ($propertyMetadata->hasWritable()) {
            $this->setWritable($propertyMetadata->isWritable());
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

        if ($propertyMetadata->hasXmlAttribute()) {
            $this->setXmlAttribute($propertyMetadata->isXmlAttribute());
        }

        if ($propertyMetadata->hasXmlValue()) {
            $this->setXmlValue($propertyMetadata->isXmlValue());
        }

        if ($propertyMetadata->hasXmlInline()) {
            $this->setXmlInline($propertyMetadata->isXmlInline());
        }

        if ($propertyMetadata->hasXmlEntry()) {
            $this->setXmlEntry($propertyMetadata->getXmlEntry());
        }

        if ($propertyMetadata->hasXmlEntryAttribute()) {
            $this->setXmlEntryAttribute($propertyMetadata->getXmlEntryAttribute());
        }

        if ($propertyMetadata->hasXmlKeyAsAttribute()) {
            $this->setXmlKeyAsAttribute($propertyMetadata->useXmlKeyAsAttribute());
        }

        if ($propertyMetadata->hasXmlKeyAsNode()) {
            $this->setXmlKeyAsNode($propertyMetadata->useXmlKeyAsNode());
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
            $this->xmlAttribute,
            $this->xmlValue,
            $this->xmlInline,
            $this->xmlEntry,
            $this->xmlEntryAttribute,
            $this->xmlKeyAsAttribute,
            $this->xmlKeyAsNode,
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
            $this->groups,
            $this->xmlAttribute,
            $this->xmlValue,
            $this->xmlInline,
            $this->xmlEntry,
            $this->xmlEntryAttribute,
            $this->xmlKeyAsAttribute,
            $this->xmlKeyAsNode
        ) = unserialize($serialized);
    }
}
