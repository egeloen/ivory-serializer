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
     * @param TypeRegistryInterface|null $typeRegistry
     */
    public function __construct(TypeRegistryInterface $typeRegistry = null)
    {
        $this->typeRegistry = $typeRegistry ?: TypeRegistry::create();
    }

    /**
     * {@inheritdoc}
     */
    public function navigate($data, ContextInterface $context, TypeMetadataInterface $type = null)
    {
        $type = $type ?: new TypeMetadata(is_object($data) ? get_class($data) : strtolower(gettype($data)));

        return $this->typeRegistry->getType($type->getName())->convert($data, $type, $context);
    }
}
