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
use Ivory\Serializer\Exclusion\ExclusionStrategy;
use Ivory\Serializer\Exclusion\ExclusionStrategyInterface;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\MetadataInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Naming\IdenticalNamingStrategy;
use Ivory\Serializer\Naming\NamingStrategyInterface;
use Ivory\Serializer\Navigator\NavigatorInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractVisitor implements VisitorInterface
{
    /**
     * @var ExclusionStrategyInterface
     */
    private $exclusionStrategy;

    /**
     * @var NamingStrategyInterface
     */
    private $namingStrategy;

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
     * @param ExclusionStrategyInterface|null $exclusionStrategy
     * @param NamingStrategyInterface|null    $namingStrategy
     */
    public function __construct(
        ExclusionStrategyInterface $exclusionStrategy = null,
        NamingStrategyInterface $namingStrategy = null
    ) {
        $this->exclusionStrategy = $exclusionStrategy ?: ExclusionStrategy::create();
        $this->namingStrategy = $namingStrategy ?: new IdenticalNamingStrategy();
    }

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
        $this->enterScope($data, $type, $context);

        if ($this->exclusionStrategy->skipType($type, $context)) {
            $data = [];
        }

        $result = $this->doVisitArray($data, $type, $context);
        $this->leaveScope($context);

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
        if ($this->exclusionStrategy->skipClass($class, $context)) {
            return false;
        }

        $this->enterScope($data, $class, $context);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjectProperty($data, PropertyMetadataInterface $property, ContextInterface $context)
    {
        $visited = false;

        if (!$this->exclusionStrategy->skipProperty($property, $context)) {
            $this->enterScope($data, $property, $context);

            $visited = $this->doVisitObjectProperty(
                $data,
                $property->hasAlias() ? $property->getAlias() : $this->namingStrategy->convert($property->getName()),
                $property,
                $context
            );

            $this->leaveScope($context);
        }

        return $visited;
    }

    /**
     * {@inheritdoc}
     */
    public function finishVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context)
    {
        $this->leaveScope($context);
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
     * @param ContextInterface  $context
     */
    private function enterScope($data, MetadataInterface $metadata, ContextInterface $context)
    {
        if ($context->hasMaxDepthEnabled()) {
            $this->dataStack->push($data);
            $this->metadataStack->push($metadata);
        }
    }

    /**
     * @param ContextInterface $context
     */
    private function leaveScope(ContextInterface $context)
    {
        if ($context->hasMaxDepthEnabled()) {
            $this->dataStack->pop();
            $this->metadataStack->pop();
        }
    }
}
