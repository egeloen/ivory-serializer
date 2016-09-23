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

        $properties = $classMetadata->getProperties();
        $policy = $this->getExclusionPolicy($data);

        foreach ($data['properties'] as $property => $value) {
            $propertyMetadata = $classMetadata->getProperty($property) ?: new PropertyMetadata($property);
            $this->loadPropertyMetadata($propertyMetadata, $value);

            if ($this->isPropertyMetadataExposed($value, $policy)) {
                $properties[$property] = $propertyMetadata;
            }
        }

        if (($order = $this->getOrder($data, $properties)) !== null) {
            $properties = $this->sortProperties($properties, $order);
        }

        $classMetadata->setProperties($properties);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param mixed                     $data
     */
    private function loadPropertyMetadata(PropertyMetadataInterface $propertyMetadata, $data)
    {
        if (!is_array($data)) {
            return;
        }

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
