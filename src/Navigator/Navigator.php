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
use Ivory\Serializer\Type\Guesser\TypeGuesser;
use Ivory\Serializer\Type\Guesser\TypeGuesserInterface;
use Ivory\Serializer\Type\Type;

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
     * @var TypeGuesserInterface
     */
    private $typeGuesser;

    /**
     * @param TypeRegistryInterface|null $typeRegistry
     * @param TypeGuesserInterface|null  $typeGuesser
     */
    public function __construct(TypeRegistryInterface $typeRegistry = null, TypeGuesserInterface $typeGuesser = null)
    {
        $this->typeRegistry = $typeRegistry ?: TypeRegistry::create();
        $this->typeGuesser = $typeGuesser ?: new TypeGuesser();
    }

    /**
     * {@inheritdoc}
     */
    public function navigate($data, ContextInterface $context, TypeMetadataInterface $type = null)
    {
        $type = $type ?: $this->typeGuesser->guess($data);
        $name = $type->getName();

        if ($data === null) {
            $name = Type::NULL;
        }

        return $this->typeRegistry->getType($name, $context->getDirection())->convert($data, $type, $context);
    }
}
