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

use Ivory\Serializer\Type\Parser\TypeParserInterface;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ReflectionClassMetadataLoader extends AbstractReflectionClassMetadataLoader
{
    /**
     * @var PropertyInfoExtractorInterface|null
     */
    private $extractor;

    /**
     * @param PropertyInfoExtractorInterface|null $extractor
     * @param TypeParserInterface|null            $typeParser
     */
    public function __construct(
        PropertyInfoExtractorInterface $extractor = null,
        TypeParserInterface $typeParser = null
    ) {
        parent::__construct($typeParser);

        $this->extractor = $extractor;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadProperty(\ReflectionProperty $property)
    {
        $result = [];
        $type = $this->loadPropertyType($property);

        if ($type !== null) {
            $result['type'] = $type;
        }

        return $result;
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    private function loadPropertyType(\ReflectionProperty $property)
    {
        if ($this->extractor === null) {
            return;
        }

        $types = $this->extractor->getTypes($property->class, $property->name);

        if (empty($types)) {
            return;
        }

        $extractedType = current($types);
        $type = $extractedType->getBuiltinType();

        if ($type === 'object') {
            $type = $extractedType->getClassName();
        }

        return $type;
    }
}
