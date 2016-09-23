<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Navigator;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\TypeMetadata;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Registry\TypeRegistry;
use Ivory\Serializer\Registry\TypeRegistryInterface;
use Ivory\Serializer\Type\Parser\TypeParser;
use Ivory\Serializer\Type\Parser\TypeParserInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Navigator implements NavigatorInterface
{
    /**
     * @var TypeRegistryInterface
     */
    private $typeRegistry;

    /**
     * @var TypeParserInterface
     */
    private $typeParser;

    /**
     * @param TypeRegistryInterface|null $typeRegistry
     * @param TypeParserInterface|null   $typeParser
     */
    public function __construct(TypeRegistryInterface $typeRegistry = null, TypeParserInterface $typeParser = null)
    {
        $this->typeRegistry = $typeRegistry ?: TypeRegistry::create();
        $this->typeParser = $typeParser ?: new TypeParser();
    }

    /**
     * {@inheritdoc}
     */
    public function navigate($data, $type, ContextInterface $context)
    {
        if ($type === null) {
            $type = new TypeMetadata(is_object($data) ? get_class($data) : strtolower(gettype($data)));
        }

        if (!$type instanceof TypeMetadataInterface) {
            $type = $this->typeParser->parse($type);
        }

        return $this->typeRegistry->getType($type->getName())->convert($data, $type, $context);
    }
}
