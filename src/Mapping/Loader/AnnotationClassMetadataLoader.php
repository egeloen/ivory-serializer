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
use Ivory\Serializer\Mapping\Annotation\Groups;
use Ivory\Serializer\Mapping\Annotation\MaxDepth;
use Ivory\Serializer\Mapping\Annotation\Since;
use Ivory\Serializer\Mapping\Annotation\Type;
use Ivory\Serializer\Mapping\Annotation\Until;
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
            if ($annotation instanceof Type) {
                $definition['type'] = $annotation->getType();
            } elseif ($annotation instanceof Since) {
                $definition['since'] = $annotation->getVersion();
            } elseif ($annotation instanceof Until) {
                $definition['until'] = $annotation->getVersion();
            } elseif ($annotation instanceof MaxDepth) {
                $definition['max_depth'] = $annotation->getMaxDepth();
            } elseif ($annotation instanceof Groups) {
                $definition['groups'] = $annotation->getGroups();
            }
        }

        return $definition;
    }
}
