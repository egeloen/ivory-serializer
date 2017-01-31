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
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
     * @var OptionsResolver|null
     */
    private $classResolver;

    /**
     * @var OptionsResolver|null
     */
    private $propertyResolver;

    /**
     * @var \Closure|null
     */
    private $emptyValidator;

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

        if ($this->classResolver === null) {
            $this->configureClassOptions($this->classResolver = new OptionsResolver());
        }

        try {
            $data = $this->classResolver->resolve($data);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException(sprintf(
                'The mapping for the class "%s" is not valid.',
                $class
            ), 0, $e);
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
        $properties = $data['exclusion_policy'] === ExclusionPolicy::NONE ? $classMetadata->getProperties() : [];

        foreach ($data['properties'] as $property => $value) {
            $propertyMetadata = $classMetadata->getProperty($property);

            if ($propertyMetadata === null) {
                $propertyMetadata = new PropertyMetadata($property, $classMetadata->getName());
            }

            $this->loadPropertyMetadata($propertyMetadata, $value);

            if ($this->isPropertyExposed($value, $data['exclusion_policy'])) {
                $properties[$property] = $propertyMetadata;
            } else {
                unset($properties[$property]);
            }
        }

        if (isset($data['order'])) {
            $properties = $this->sortProperties($properties, $data['order']);
        }

        if (isset($data['xml_root'])) {
            $classMetadata->setXmlRoot($data['xml_root']);
        }

        $classMetadata->setProperties($properties);
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param mixed                     $data
     */
    private function loadPropertyMetadata(PropertyMetadataInterface $propertyMetadata, $data)
    {
        if (isset($data['alias'])) {
            $propertyMetadata->setAlias($data['alias']);
        }

        if (isset($data['type'])) {
            $propertyMetadata->setType($this->typeParser->parse($data['type']));
        }

        if (isset($data['readable'])) {
            $propertyMetadata->setReadable($data['readable']);
        }

        if (isset($data['writable'])) {
            $propertyMetadata->setWritable($data['writable']);
        }

        if (isset($data['accessor'])) {
            $propertyMetadata->setAccessor($data['accessor']);
        }

        if (isset($data['mutator'])) {
            $propertyMetadata->setMutator($data['mutator']);
        }

        if (isset($data['since'])) {
            $propertyMetadata->setSinceVersion($data['since']);
        }

        if (isset($data['until'])) {
            $propertyMetadata->setUntilVersion($data['until']);
        }

        if (isset($data['max_depth'])) {
            $propertyMetadata->setMaxDepth($data['max_depth']);
        }

        if (isset($data['groups'])) {
            $propertyMetadata->addGroups($data['groups']);
        }

        if (isset($data['xml_attribute'])) {
            $propertyMetadata->setXmlAttribute($data['xml_attribute']);
        }

        if (isset($data['xml_value'])) {
            $propertyMetadata->setXmlValue($data['xml_value']);
        }

        if (isset($data['xml_inline'])) {
            $propertyMetadata->setXmlInline($data['xml_inline']);

            if (!isset($data['xml_key_as_attribute'])) {
                $data['xml_key_as_attribute'] = true;
            }

            if (!isset($data['xml_key_as_node'])) {
                $data['xml_key_as_node'] = false;
            }
        }

        if (isset($data['xml_entry'])) {
            $propertyMetadata->setXmlEntry($data['xml_entry']);
        }

        if (isset($data['xml_entry_attribute'])) {
            $propertyMetadata->setXmlEntryAttribute($data['xml_entry_attribute']);
        }

        if (isset($data['xml_key_as_attribute'])) {
            $propertyMetadata->setXmlKeyAsAttribute($data['xml_key_as_attribute']);
        }

        if (isset($data['xml_key_as_node'])) {
            $propertyMetadata->setXmlKeyAsNode($data['xml_key_as_node']);
        }
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
     * @param mixed[] $property
     * @param string  $policy
     *
     * @return bool
     */
    private function isPropertyExposed($property, $policy)
    {
        $expose = isset($property['expose']) && $property['expose'];
        $exclude = isset($property['exclude']) && $property['exclude'];

        return ($policy === ExclusionPolicy::ALL && $expose) || ($policy === ExclusionPolicy::NONE && !$exclude);
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configureClassOptions(OptionsResolver $resolver)
    {
        $emptyValidator = $this->getEmptyValidator();

        $resolver
            ->setRequired(['properties'])
            ->setDefaults(['exclusion_policy' => ExclusionPolicy::NONE])
            ->setDefined([
                'readable',
                'order',
                'writable',
                'xml_root',
            ])
            ->setAllowedTypes('order', ['array', 'string'])
            ->setAllowedTypes('properties', 'array')
            ->setAllowedTypes('readable', 'bool')
            ->setAllowedTypes('writable', 'bool')
            ->setAllowedTypes('xml_root', 'string')
            ->setAllowedValues('exclusion_policy', [ExclusionPolicy::NONE, ExclusionPolicy::ALL])
            ->setAllowedValues('order', $emptyValidator)
            ->setAllowedValues('xml_root', $emptyValidator)
            ->setAllowedValues('properties', function ($properties) {
                return count($properties) > 0;
            })
            ->setNormalizer('order', function (Options $options, $order) use ($emptyValidator) {
                if (is_string($order)) {
                    if (strcasecmp($order, 'ASC') === 0 || strcasecmp($order, 'DESC') === 0) {
                        return strtoupper($order);
                    }

                    $order = array_map('trim', explode(',', $order));
                }

                if (is_array($order)) {
                    $properties = $options['properties'];

                    foreach ($order as $property) {
                        if (!isset($properties[$property])) {
                            throw new InvalidOptionsException(sprintf(
                                'The property "%s" defined in the mapping order does not exist.',
                                $property
                            ));
                        }
                    }
                }

                return $order;
            })
            ->setNormalizer('properties', function (Options $options, array $properties) {
                if ($this->propertyResolver === null) {
                    $this->configurePropertyOptions($this->propertyResolver = new OptionsResolver());
                }

                $results = [];
                $visibilities = ['readable', 'writable'];

                foreach ($properties as $key => $property) {
                    if ($property === null) {
                        $property = [];
                    }

                    try {
                        $results[$key] = $this->propertyResolver->resolve($property);
                    } catch (\InvalidArgumentException $e) {
                        throw new \InvalidArgumentException(sprintf(
                            'The mapping for the property "%s" is not valid.',
                            $key
                        ), 0, $e);
                    }

                    foreach ($visibilities as $visibility) {
                        if (isset($options[$visibility]) && !isset($results[$key][$visibility])) {
                            $results[$key][$visibility] = $options[$visibility];
                        }
                    }
                }

                return $results;
            });
    }

    /**
     * @param OptionsResolver $resolver
     */
    private function configurePropertyOptions(OptionsResolver $resolver)
    {
        $emptyValidator = $this->getEmptyValidator();

        $resolver
            ->setDefined([
                'accessor',
                'alias',
                'exclude',
                'expose',
                'groups',
                'max_depth',
                'mutator',
                'readable',
                'since',
                'type',
                'until',
                'writable',
                'xml_attribute',
                'xml_entry',
                'xml_entry_attribute',
                'xml_inline',
                'xml_key_as_attribute',
                'xml_key_as_node',
                'xml_value',
            ])
            ->setAllowedTypes('accessor', 'string')
            ->setAllowedTypes('alias', 'string')
            ->setAllowedTypes('exclude', 'bool')
            ->setAllowedTypes('expose', 'bool')
            ->setAllowedTypes('groups', 'array')
            ->setAllowedTypes('max_depth', 'int')
            ->setAllowedTypes('mutator', 'string')
            ->setAllowedTypes('readable', 'bool')
            ->setAllowedTypes('since', 'string')
            ->setAllowedTypes('type', 'string')
            ->setAllowedTypes('until', 'string')
            ->setAllowedTypes('writable', 'bool')
            ->setAllowedTypes('xml_attribute', 'bool')
            ->setAllowedTypes('xml_entry', 'string')
            ->setAllowedTypes('xml_entry_attribute', 'string')
            ->setAllowedTypes('xml_inline', 'bool')
            ->setAllowedTypes('xml_key_as_attribute', 'bool')
            ->setAllowedTypes('xml_key_as_node', 'bool')
            ->setAllowedTypes('xml_value', 'bool')
            ->setAllowedValues('accessor', $emptyValidator)
            ->setAllowedValues('alias', $emptyValidator)
            ->setAllowedValues('mutator', $emptyValidator)
            ->setAllowedValues('since', $emptyValidator)
            ->setAllowedValues('type', $emptyValidator)
            ->setAllowedValues('until', $emptyValidator)
            ->setAllowedValues('xml_entry', $emptyValidator)
            ->setAllowedValues('xml_entry_attribute', $emptyValidator)
            ->setAllowedValues('groups', function (array $groups) use ($emptyValidator) {
                foreach ($groups as $group) {
                    if (!is_string($group) || !call_user_func($emptyValidator, $group)) {
                        return false;
                    }
                }

                return count($groups) > 0;
            })
            ->setAllowedValues('max_depth', function ($maxDepth) {
                return $maxDepth >= 0;
            });
    }

    /**
     * @return \Closure
     */
    private function getEmptyValidator()
    {
        if ($this->emptyValidator === null) {
            $this->emptyValidator = function ($value) {
                return !empty($value);
            };
        }

        return $this->emptyValidator;
    }
}
