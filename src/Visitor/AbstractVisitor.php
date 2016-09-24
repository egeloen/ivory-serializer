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
use Ivory\Serializer\Mapping\MetadataInterface;
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
    private $navigator;

    /**
     * @var \SplStack
     */
    private $dataStack;

    /**
     * @var \SplStack
     */
    private $metadataStack;

    /**
     * {@inheritdoc}
     */
    public function prepare($data, ContextInterface $context)
    {
        $this->navigator = $context->getNavigator();
        $this->dataStack = $context->getDataStack();
        $this->metadataStack = $context->getMetadataStack();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $this->enterScope($data, $type);

        if ($context->getExclusionStrategy()->skipType($type, $context)) {
            $data = [];
        }

        $result = $this->doVisitArray($data, $type, $context);
        $this->leaveScope();

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
        if ($context->getExclusionStrategy()->skipClass($class, $context)) {
            return false;
        }

        $this->enterScope($data, $class);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjectProperty($data, PropertyMetadataInterface $property, ContextInterface $context)
    {
        $visited = false;

        if (!$context->getExclusionStrategy()->skipProperty($property, $context)) {
            $this->enterScope($data, $property);

            $name = $property->hasAlias()
                ? $property->getAlias()
                : $context->getNamingStrategy()->convert($property->getName());

            $visited = $this->doVisitObjectProperty($data, $name, $property, $context);
            $this->leaveScope();
        }

        return $visited;
    }

    /**
     * {@inheritdoc}
     */
    public function finishVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context)
    {
        $this->leaveScope();
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

    /**
     * @param mixed                      $data
     * @param ContextInterface           $context
     * @param TypeMetadataInterface|null $type
     *
     * @return mixed
     */
    protected function navigate($data, ContextInterface $context, TypeMetadataInterface $type = null)
    {
        return $this->navigator->navigate($data, $context, $type);
    }

    /**
     * @param mixed             $data
     * @param MetadataInterface $metadata
     */
    private function enterScope($data, MetadataInterface $metadata)
    {
        $this->dataStack->push($data);
        $this->metadataStack->push($metadata);
    }

    private function leaveScope()
    {
        $this->dataStack->pop();
        $this->metadataStack->pop();
    }
}
