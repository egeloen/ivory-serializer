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
     *
     * @return bool
     */
    private function doLoadClassMetadata(ClassMetadataInterface $classMetadata, array $data)
    {
        if (!isset($data['properties']) || empty($data['properties'])) {
            throw new \InvalidArgumentException(sprintf(
                'No mapping properties found for "%s".',
                $classMetadata->getName()
            ));
        }

        foreach ($data['properties'] as $property => $value) {
            $propertyMetadata = $classMetadata->getProperty($property) ?: new PropertyMetadata($property);
            $this->loadPropertyMetadata($propertyMetadata, $value);
            $classMetadata->addProperty($propertyMetadata);
        }
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
}
