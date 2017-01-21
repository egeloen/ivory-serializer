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

use Ivory\Serializer\Exclusion\ExclusionPolicy;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\PropertyMetadata;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Type\Parser\TypeParser;
use Ivory\Serializer\Type\Parser\TypeParserInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractClassMetadataLoader implements ClassMetadataLoaderInterface
{
    /**
     * @var TypeParserInterface
     */
    private $typeParser;

    /**
     * @var mixed[][]
     */
    private $data = [];

    /**
     * @param TypeParserInterface|null $typeParser
     */
    public function __construct(TypeParserInterface $typeParser = null)
    {
        $this->typeParser = $typeParser ?: new TypeParser();
    }

    /**
     * {@inheritdoc}
     */
    public function loadClassMetadata(ClassMetadataInterface $classMetadata)
    {
        $class = $classMetadata->getName();

        if (!array_key_exists($class, $this->data)) {
            $this->data[$class] = $this->loadData($class);
        }

        if (!is_array($data = $this->data[$class])) {
            return false;
        }

        $this->doLoadClassMetadata($classMetadata, $data);

        return true;
    }

    /**
     * @param string $class
     *
     * @return mixed[]|null
     */
    abstract protected function loadData($class);

    /**
     * @param ClassMetadataInterface $classMetadata
     * @param mixed[]                $data
     */
    private function doLoadClassMetadata(ClassMetadataInterface $classMetadata, array $data)
    {
        if (!isset($data['properties']) || empty($data['properties'])) {
            throw new \InvalidArgumentException(sprintf(
                'No mapping properties found for "%s".',
                $classMetadata->getName()
            ));
        }

        $policy = $this->getExclusionPolicy($data);
        $readableClass = $this->getReadable($data);
        $writableClass = $this->getWritable($data);
        $properties = $classMetadata->getProperties();

        foreach ($data['properties'] as $property => $value) {
            $propertyMetadata = $classMetadata->getProperty($property)
                ?: new PropertyMetadata($property, $classMetadata->getName());

            $this->loadPropertyMetadata($propertyMetadata, $value, $readableClass, $writableClass);

            if ($this->isPropertyMetadataExposed($value, $policy)) {
                $properties[$property] = $propertyMetadata;
            } else {
                unset($properties[$property]);
            }
        }

        if (($order = $this->getOrder($data, $properties)) !== null) {
            $properties = $this->sortProperties($properties, $order);
        }

        $classMetadata->setProperties($properties);

        if (array_key_exists('xml_root', $data)) {
            $this->loadClassMetadataXmlRoot($classMetadata, $data['xml_root']);
        }
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param mixed                     $data
     * @param bool                      $classReadable
     * @param bool                      $classWritable
     */
    private function loadPropertyMetadata(
        PropertyMetadataInterface $propertyMetadata,
        $data,
        $classReadable,
        $classWritable
    ) {
        if (!is_array($data)) {
            $data = [];
        }

        $propertyMetadata->setReadable($this->getReadable($data, $classReadable));
        $propertyMetadata->setWritable($this->getWritable($data, $classWritable));

        if (array_key_exists('exclude', $data)) {
            $this->validatePropertyMetadataExclude($data['exclude']);
        }

        if (array_key_exists('expose', $data)) {
            $this->validatePropertyMetadataExpose($data['expose']);
        }

        if (array_key_exists('alias', $data)) {
            $this->loadPropertyMetadataAlias($propertyMetadata, $data['alias']);
        }

        if (array_key_exists('type', $data)) {
            $this->loadPropertyMetadataType($propertyMetadata, $data['type']);
        }

        if (array_key_exists('accessor', $data)) {
            $this->loadPropertyMetadataAccessor($propertyMetadata, $data['accessor']);
        }

        if (array_key_exists('mutator', $data)) {
            $this->loadPropertyMetadataMutator($propertyMetadata, $data['mutator']);
        }

        if (array_key_exists('since', $data)) {
            $this->loadPropertyMetadataSinceVersion($propertyMetadata, $data['since']);
        }

        if (array_key_exists('until', $data)) {
            $this->loadPropertyMetadataUntilVersion($propertyMetadata, $data['until']);
        }

        if (array_key_exists('max_depth', $data)) {
            $this->loadPropertyMetadataMaxDepth($propertyMetadata, $data['max_depth']);
        }

        if (array_key_exists('groups', $data)) {
            $this->loadPropertyMetadataGroups($propertyMetadata, $data['groups']);
        }

        if (array_key_exists('xml_attribute', $data)) {
            $this->loadPropertyMetadataXmlAttribute($propertyMetadata, $data['xml_attribute']);
        }

        if (array_key_exists('xml_value', $data)) {
            $this->loadPropertyMetadataXmlValue($propertyMetadata, $data['xml_value']);
        }

        if (array_key_exists('xml_inline', $data)) {
            $this->loadPropertyMetadataXmlInline($propertyMetadata, $data['xml_inline']);

            if (!array_key_exists('xml_key_as_attribute', $data)) {
                $data['xml_key_as_attribute'] = true;
            }

            if (!array_key_exists('xml_key_as_node', $data)) {
                $data['xml_key_as_node'] = false;
            }
        }

        if (array_key_exists('xml_entry', $data)) {
            $this->loadPropertyMetadataXmlEntry($propertyMetadata, $data['xml_entry']);
        }

        if (array_key_exists('xml_entry_attribute', $data)) {
            $this->loadPropertyMetadataXmlEntryAttribute($propertyMetadata, $data['xml_entry_attribute']);
        }

        if (array_key_exists('xml_key_as_attribute', $data)) {
            $this->loadPropertyMetadataXmlKeyAsAttribute($propertyMetadata, $data['xml_key_as_attribute']);
        }

        if (array_key_exists('xml_key_as_node', $data)) {
            $this->loadPropertyMetadataXmlKeyAsNode($propertyMetadata, $data['xml_key_as_node']);
        }
    }

    /**
     * @param ClassMetadataInterface $classMetadata
     * @param string                 $xmlRoot
     */
    private function loadClassMetadataXmlRoot(ClassMetadataInterface $classMetadata, $xmlRoot)
    {
        if (!is_string($xmlRoot)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping xml root must be a non empty string, got "%s".',
                is_object($xmlRoot) ? get_class($xmlRoot) : gettype($xmlRoot)
            ));
        }

        if (empty($xmlRoot)) {
            throw new \InvalidArgumentException('The mapping xml root must be a non empty string.');
        }

        $classMetadata->setXmlRoot($xmlRoot);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string                    $alias
     */
    private function loadPropertyMetadataAlias(PropertyMetadataInterface $propertyMetadata, $alias)
    {
        if (!is_string($alias)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property alias must be a non empty string, got "%s".',
                is_object($alias) ? get_class($alias) : gettype($alias)
            ));
        }

        $alias = trim($alias);

        if (empty($alias)) {
            throw new \InvalidArgumentException('The mapping property alias must be a non empty string.');
        }

        $propertyMetadata->setAlias($alias);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string                    $type
     */
    private function loadPropertyMetadataType(PropertyMetadataInterface $propertyMetadata, $type)
    {
        if (!is_string($type)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property type must be a non empty string, got "%s".',
                is_object($type) ? get_class($type) : gettype($type)
            ));
        }

        $propertyMetadata->setType($this->typeParser->parse($type));
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string                    $accessor
     */
    private function loadPropertyMetadataAccessor(PropertyMetadataInterface $propertyMetadata, $accessor)
    {
        if (!is_string($accessor)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property accessor must be a non empty string, got "%s".',
                is_object($accessor) ? get_class($accessor) : gettype($accessor)
            ));
        }

        $accessor = trim($accessor);

        if (empty($accessor)) {
            throw new \InvalidArgumentException('The mapping property accessor must be a non empty string.');
        }

        $propertyMetadata->setAccessor($accessor);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string                    $mutator
     */
    private function loadPropertyMetadataMutator(PropertyMetadataInterface $propertyMetadata, $mutator)
    {
        if (!is_string($mutator)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property mutator must be a non empty string, got "%s".',
                is_object($mutator) ? get_class($mutator) : gettype($mutator)
            ));
        }

        $mutator = trim($mutator);

        if (empty($mutator)) {
            throw new \InvalidArgumentException('The mapping property mutator must be a non empty string.');
        }

        $propertyMetadata->setMutator($mutator);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string                    $version
     */
    private function loadPropertyMetadataSinceVersion(PropertyMetadataInterface $propertyMetadata, $version)
    {
        if (!is_string($version)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property since version must be a non empty string, got "%s".',
                is_object($version) ? get_class($version) : gettype($version)
            ));
        }

        $version = trim($version);

        if (empty($version)) {
            throw new \InvalidArgumentException('The mapping property since version must be a non empty string.');
        }

        $propertyMetadata->setSinceVersion($version);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string                    $version
     */
    private function loadPropertyMetadataUntilVersion(PropertyMetadataInterface $propertyMetadata, $version)
    {
        if (!is_string($version)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property until version must be a non empty string, got "%s".',
                is_object($version) ? get_class($version) : gettype($version)
            ));
        }

        $version = trim($version);

        if (empty($version)) {
            throw new \InvalidArgumentException('The mapping property until version must be a non empty string.');
        }

        $propertyMetadata->setUntilVersion($version);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string|int                $maxDepth
     */
    private function loadPropertyMetadataMaxDepth(PropertyMetadataInterface $propertyMetadata, $maxDepth)
    {
        if (!is_int($maxDepth) && !is_string($maxDepth) && !ctype_digit($maxDepth)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property max depth must be a positive integer, got "%s".',
                is_object($maxDepth) ? get_class($maxDepth) : gettype($maxDepth)
            ));
        }

        $maxDepth = (int) $maxDepth;

        if ($maxDepth <= 0) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property max depth must be a positive integer, got "%d".',
                $maxDepth
            ));
        }

        $propertyMetadata->setMaxDepth($maxDepth);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string[]                  $groups
     */
    private function loadPropertyMetadataGroups(PropertyMetadataInterface $propertyMetadata, $groups)
    {
        if (!is_array($groups)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property groups must be an array of non empty strings, got "%s".',
                is_object($groups) ? get_class($groups) : gettype($groups)
            ));
        }

        foreach ($groups as $group) {
            if (!is_string($group)) {
                throw new \InvalidArgumentException(sprintf(
                    'The mapping property groups must be an array of non empty strings, got "%s".',
                    is_object($group) ? get_class($group) : gettype($group)
                ));
            }

            $group = trim($group);

            if (empty($group)) {
                throw new \InvalidArgumentException(
                    'The mapping property groups must be an array of non empty strings.'
                );
            }

            $propertyMetadata->addGroup($group);
        }
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param bool                      $xmlAttribute
     */
    private function loadPropertyMetadataXmlAttribute(PropertyMetadataInterface $propertyMetadata, $xmlAttribute)
    {
        if (!is_bool($xmlAttribute)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property xml attribute must be a boolean, got "%s".',
                is_object($xmlAttribute) ? get_class($xmlAttribute) : gettype($xmlAttribute)
            ));
        }

        $propertyMetadata->setXmlAttribute($xmlAttribute);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param bool                      $xmlValue
     */
    private function loadPropertyMetadataXmlValue(PropertyMetadataInterface $propertyMetadata, $xmlValue)
    {
        if (!is_bool($xmlValue)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property xml value must be a boolean, got "%s".',
                is_object($xmlValue) ? get_class($xmlValue) : gettype($xmlValue)
            ));
        }

        $propertyMetadata->setXmlValue($xmlValue);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param bool                      $xmlInline
     */
    private function loadPropertyMetadataXmlInline(PropertyMetadataInterface $propertyMetadata, $xmlInline)
    {
        if (!is_bool($xmlInline)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property xml inline must be a boolean, got "%s".',
                is_object($xmlInline) ? get_class($xmlInline) : gettype($xmlInline)
            ));
        }

        $propertyMetadata->setXmlInline($xmlInline);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string                    $xmlEntry
     */
    private function loadPropertyMetadataXmlEntry(PropertyMetadataInterface $propertyMetadata, $xmlEntry)
    {
        if (!is_string($xmlEntry)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property xml entry must be a non empty string, got "%s".',
                is_object($xmlEntry) ? get_class($xmlEntry) : gettype($xmlEntry)
            ));
        }

        if (empty($xmlEntry)) {
            throw new \InvalidArgumentException('The mapping property xml entry must be a non empty string.');
        }

        $propertyMetadata->setXmlEntry($xmlEntry);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string                    $xmlEntryAttribute
     */
    private function loadPropertyMetadataXmlEntryAttribute(
        PropertyMetadataInterface $propertyMetadata,
        $xmlEntryAttribute
    ) {
        if (!is_string($xmlEntryAttribute)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property xml entry attribute must be a non empty string, got "%s".',
                is_object($xmlEntryAttribute) ? get_class($xmlEntryAttribute) : gettype($xmlEntryAttribute)
            ));
        }

        if (empty($xmlEntryAttribute)) {
            throw new \InvalidArgumentException(
                'The mapping property xml entry attribute must be a non empty string.'
            );
        }

        $propertyMetadata->setXmlEntryAttribute($xmlEntryAttribute);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string                    $xmlKeyAsAttribute
     */
    private function loadPropertyMetadataXmlKeyAsAttribute(
        PropertyMetadataInterface $propertyMetadata,
        $xmlKeyAsAttribute
    ) {
        if (!is_bool($xmlKeyAsAttribute)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property xml key as attribute must be a boolean, got "%s".',
                is_object($xmlKeyAsAttribute) ? get_class($xmlKeyAsAttribute) : gettype($xmlKeyAsAttribute)
            ));
        }

        $propertyMetadata->setXmlKeyAsAttribute($xmlKeyAsAttribute);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param string                    $xmlKeyAsNode
     */
    private function loadPropertyMetadataXmlKeyAsNode(PropertyMetadataInterface $propertyMetadata, $xmlKeyAsNode)
    {
        if (!is_bool($xmlKeyAsNode)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property xml key as node must be a boolean, got "%s".',
                is_object($xmlKeyAsNode) ? get_class($xmlKeyAsNode) : gettype($xmlKeyAsNode)
            ));
        }

        $propertyMetadata->setXmlKeyAsNode($xmlKeyAsNode);
    }

    /**
     * @param mixed[] $data
     *
     * @return string|null
     */
    private function getExclusionPolicy(array $data)
    {
        if (!isset($data['exclusion_policy'])) {
            return ExclusionPolicy::NONE;
        }

        $policy = $data['exclusion_policy'];

        if (!is_string($policy)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping exclusion policy must be "%s" or "%s", got "%s".',
                ExclusionPolicy::ALL,
                ExclusionPolicy::NONE,
                is_object($policy) ? get_class($policy) : gettype($policy)
            ));
        }

        $policy = strtolower(trim($policy));

        if ($policy !== ExclusionPolicy::ALL && $policy !== ExclusionPolicy::NONE) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping exclusion policy must be "%s" or "%s", got "%s".',
                ExclusionPolicy::ALL,
                ExclusionPolicy::NONE,
                $policy
            ));
        }

        return $policy;
    }

    /**
     * @param mixed[] $data
     * @param bool    $default
     *
     * @return bool|null
     */
    private function getReadable(array $data, $default = true)
    {
        if (!array_key_exists('readable', $data)) {
            return $default;
        }

        $readable = $data['readable'];

        if (!is_bool($readable)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping readable must be a boolean, got "%s".',
                is_object($readable) ? get_class($readable) : gettype($readable)
            ));
        }

        return $readable;
    }

    /**
     * @param mixed[] $data
     * @param bool    $default
     *
     * @return bool|null
     */
    private function getWritable(array $data, $default = true)
    {
        if (!array_key_exists('writable', $data)) {
            return $default;
        }

        $writable = $data['writable'];

        if (!is_bool($writable)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping readable must be a boolean, got "%s".',
                is_object($writable) ? get_class($writable) : gettype($writable)
            ));
        }

        return $writable;
    }

    /**
     * @param mixed[]                     $data
     * @param PropertyMetadataInterface[] $properties
     *
     * @return string|string[]|null
     */
    private function getOrder(array $data, array $properties)
    {
        if (!isset($data['order'])) {
            return;
        }

        $order = $data['order'];

        if (is_string($order)) {
            $order = trim($order);

            if (empty($order)) {
                throw new \InvalidArgumentException(
                    'The mapping order must be an non empty strings or an array of non empty strings.'
                );
            }

            if (strcasecmp($order, 'ASC') === 0 || strcasecmp($order, 'DESC') === 0) {
                return strtoupper($order);
            }

            $order = explode(',', $order);
        } elseif (!is_array($order)) {
            throw new \InvalidArgumentException(
                'The mapping order must be an non empty strings or an array of non empty strings.'
            );
        }

        if (empty($order)) {
            throw new \InvalidArgumentException(
                'The mapping order must be an non empty strings or an array of non empty strings.'
            );
        }

        foreach ($order as &$property) {
            if (!is_string($property)) {
                throw new \InvalidArgumentException(sprintf(
                    'The mapping order must be an non empty strings or an array of non empty strings, got "%s".',
                    is_object($property) ? get_class($property) : gettype($property)
                ));
            }

            $property = trim($property);

            if (empty($property)) {
                throw new \InvalidArgumentException(
                    'The mapping order must be an non empty strings or an array of non empty strings.'
                );
            }

            if (!isset($properties[$property])) {
                throw new \InvalidArgumentException(sprintf(
                    'The property "%s" defined in the mapping order does not exist.',
                    $property
                ));
            }
        }

        return $order;
    }

    /**
     * @param PropertyMetadataInterface[] $properties
     * @param string|string[]             $order
     *
     * @return PropertyMetadataInterface[]
     */
    private function sortProperties(array $properties, $order)
    {
        if (is_string($order)) {
            if ($order === 'ASC') {
                ksort($properties);
            } else {
                krsort($properties);
            }
        } elseif (is_array($order)) {
            $properties = array_merge(array_flip($order), $properties);
        }

        return $properties;
    }

    /**
     * @param bool $exclude
     */
    private function validatePropertyMetadataExclude($exclude)
    {
        if (!is_bool($exclude)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property exclude must be a boolean, got "%s".',
                is_object($exclude) ? get_class($exclude) : gettype($exclude)
            ));
        }
    }

    /**
     * @param bool $expose
     */
    private function validatePropertyMetadataExpose($expose)
    {
        if (!is_bool($expose)) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping property expose must be a boolean, got "%s".',
                is_object($expose) ? get_class($expose) : gettype($expose)
            ));
        }
    }

    /**
     * @param mixed[] $property
     * @param string  $policy
     *
     * @return bool
     */
    private function isPropertyMetadataExposed($property, $policy)
    {
        $expose = isset($property['expose']) && $property['expose'];
        $exclude = isset($property['exclude']) && $property['exclude'];

        return ($policy === ExclusionPolicy::ALL && $expose) || ($policy === ExclusionPolicy::NONE && !$exclude);
    }
}
