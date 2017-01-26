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

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Ivory\Serializer\Mapping\Annotation\Accessor;
use Ivory\Serializer\Mapping\Annotation\Alias;
use Ivory\Serializer\Mapping\Annotation\Exclude;
use Ivory\Serializer\Mapping\Annotation\ExclusionPolicy;
use Ivory\Serializer\Mapping\Annotation\Expose;
use Ivory\Serializer\Mapping\Annotation\Groups;
use Ivory\Serializer\Mapping\Annotation\MaxDepth;
use Ivory\Serializer\Mapping\Annotation\Mutator;
use Ivory\Serializer\Mapping\Annotation\Order;
use Ivory\Serializer\Mapping\Annotation\Readable;
use Ivory\Serializer\Mapping\Annotation\Since;
use Ivory\Serializer\Mapping\Annotation\Type;
use Ivory\Serializer\Mapping\Annotation\Until;
use Ivory\Serializer\Mapping\Annotation\Writable;
use Ivory\Serializer\Mapping\Annotation\XmlAttribute;
use Ivory\Serializer\Mapping\Annotation\XmlCollection;
use Ivory\Serializer\Mapping\Annotation\XmlRoot;
use Ivory\Serializer\Mapping\Annotation\XmlValue;
use Ivory\Serializer\Type\Parser\TypeParserInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class AnnotationClassMetadataLoader extends AbstractReflectionClassMetadataLoader
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader|null              $reader
     * @param TypeParserInterface|null $typeParser
     */
    public function __construct(Reader $reader = null, TypeParserInterface $typeParser = null)
    {
        parent::__construct($typeParser);

        $this->reader = $reader ?: new AnnotationReader();
    }

    /**
     * {@inheritdoc}
     */
    protected function loadClass(\ReflectionClass $class)
    {
        $definition = parent::loadClass($class);

        foreach ($this->reader->getClassAnnotations($class) as $annotation) {
            if ($annotation instanceof ExclusionPolicy) {
                $definition['exclusion_policy'] = $annotation->policy;
            } elseif ($annotation instanceof Order) {
                $definition['order'] = $annotation->order;
            } elseif ($annotation instanceof Readable) {
                $definition['readable'] = $annotation->readable;
            } elseif ($annotation instanceof Writable) {
                $definition['writable'] = $annotation->writable;
            } elseif ($annotation instanceof XmlRoot) {
                $definition['xml_root'] = $annotation->name;
            }
        }

        return $definition;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadProperty(\ReflectionProperty $property)
    {
        return $this->loadAnnotations($this->reader->getPropertyAnnotations($property));
    }

    /**
     * {@inheritdoc}
     */
    protected function loadMethod(\ReflectionMethod $method)
    {
        $result = $this->loadAnnotations($this->reader->getMethodAnnotations($method));

        if (!empty($result)) {
            return $result;
        }
    }

    /**
     * @param object[] $annotations
     *
     * @return mixed[]
     */
    private function loadAnnotations(array $annotations)
    {
        $definition = [];

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Alias) {
                $definition['alias'] = $annotation->alias;
            } elseif ($annotation instanceof Type) {
                $definition['type'] = $annotation->type;
            } elseif ($annotation instanceof Expose) {
                $definition['expose'] = true;
            } elseif ($annotation instanceof Exclude) {
                $definition['exclude'] = true;
            } elseif ($annotation instanceof Readable) {
                $definition['readable'] = $annotation->readable;
            } elseif ($annotation instanceof Writable) {
                $definition['writable'] = $annotation->writable;
            } elseif ($annotation instanceof Accessor) {
                $definition['accessor'] = $annotation->accessor;
            } elseif ($annotation instanceof Mutator) {
                $definition['mutator'] = $annotation->mutator;
            } elseif ($annotation instanceof Since) {
                $definition['since'] = $annotation->version;
            } elseif ($annotation instanceof Until) {
                $definition['until'] = $annotation->version;
            } elseif ($annotation instanceof MaxDepth) {
                $definition['max_depth'] = $annotation->maxDepth;
            } elseif ($annotation instanceof Groups) {
                $definition['groups'] = $annotation->groups;
            } elseif ($annotation instanceof XmlAttribute) {
                $definition['xml_attribute'] = true;
            } elseif ($annotation instanceof XmlValue) {
                $definition['xml_value'] = true;
            } elseif ($annotation instanceof XmlCollection) {
                if ($annotation->entry !== null) {
                    $definition['xml_entry'] = $annotation->entry;
                }

                if ($annotation->entryAttribute !== null) {
                    $definition['xml_entry_attribute'] = $annotation->entryAttribute;
                }

                if ($annotation->keyAsAttribute !== null) {
                    $definition['xml_key_as_attribute'] = $annotation->keyAsAttribute;
                }

                if ($annotation->keyAsNode !== null) {
                    $definition['xml_key_as_node'] = $annotation->keyAsNode;
                }

                if ($annotation->inline !== null) {
                    $definition['xml_inline'] = $annotation->inline;
                }
            }
        }

        return $definition;
    }
}
