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
     * @var TypeParserInterface
     */
    private $typeParser;

    /**
     * @var TypeRegistryInterface
     */
    private $typeRegistry;

    /**
     * @param TypeParserInterface|null   $typeParser
     * @param TypeRegistryInterface|null $typeRegistry
     */
    public function __construct(TypeParserInterface $typeParser = null, TypeRegistryInterface $typeRegistry = null)
    {
        $this->typeParser = $typeParser ?: new TypeParser();
        $this->typeRegistry = $typeRegistry ?: TypeRegistry::create();
    }

    /**
     * {@inheritdoc}
     */
    public function navigate($data, $type, ContextInterface $context)
    {
        if ($type === null) {
            $type = is_object($data) ? get_class($data) : strtolower(gettype($data));
        }

        if (!$type instanceof TypeMetadataInterface) {
            $type = $this->typeParser->parse($type);
        }

        return $this->typeRegistry->getType($type->getName())->convert($data, $type, $context);
    }
}
