<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Visitor;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Navigator\NavigatorInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractVisitor implements VisitorInterface
{
    /**
     * @var NavigatorInterface
     */
    protected $navigator;

    /**
     * {@inheritdoc}
     */
    public function prepare($data, ContextInterface $context)
    {
        $this->navigator = $context->getNavigator();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $context->enterScope($data, $type);

        if ($context->getExclusionStrategy()->skipType($type, $context)) {
            $data = [];
        }

        $result = $this->doVisitArray($data, $type, $context);
        $context->leaveScope();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function visitBoolean($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $this->visitData((bool) $data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function visitFloat($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $this->visitData((float) $data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function visitInteger($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $this->visitData((int) $data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function visitNull($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $this->visitData(null, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function visitResource($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        throw new \RuntimeException('(De)-Serializing a resource is not supported.');
    }

    /**
     * {@inheritdoc}
     */
    public function visitString($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $this->visitData((string) $data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function startVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context)
    {
        $context->enterScope($data, $class);

        if ($context->getExclusionStrategy()->skipClass($class, $context)) {
            $context->leaveScope();

            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjectProperty($data, PropertyMetadataInterface $property, ContextInterface $context)
    {
        $visited = false;

        if (!$context->getExclusionStrategy()->skipProperty($property, $context)) {
            $context->enterScope($data, $property);

            $name = $property->hasAlias()
                ? $property->getAlias()
                : $context->getNamingStrategy()->convert($property->getName());

            $visited = $this->doVisitObjectProperty($data, $name, $property, $context);
            $context->leaveScope();
        }

        return $visited;
    }

    /**
     * {@inheritdoc}
     */
    public function finishVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context)
    {
        $context->leaveScope();
    }

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    abstract protected function doVisitArray($data, TypeMetadataInterface $type, ContextInterface $context);

    /**
     * @param mixed                     $data
     * @param string                    $name
     * @param PropertyMetadataInterface $property
     * @param ContextInterface          $context
     *
     * @return bool
     */
    abstract protected function doVisitObjectProperty(
        $data,
        $name,
        PropertyMetadataInterface $property,
        ContextInterface $context
    );
}
