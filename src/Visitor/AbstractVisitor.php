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
     * @var \SplStack
     */
    private $stack;

    /**
     * @var mixed
     */
    protected $result;

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
        $this->stack = new \SplStack();
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function visitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $result = [];
        $this->enterScope($data, $type, $context);

        if ($this->exclusionStrategy->skipType($type, $context)) {
            $data = [];
        }

        foreach ($data as $key => $value) {
            $result[$this->navigate($key, $type->getOption('key'), $context)] = $this->navigate(
                $value,
                $type->getOption('value'),
                $context
            );
        }

        $this->leaveScope($context);

        return $this->visitData($result, $type, $context);
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
    public function visitData($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        if ($this->result === null) {
            $this->result = $data;
        }

        return $data;
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

        $this->stack->push($this->result);
        $this->result = $this->createResult($class);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function visitObjectProperty($data, PropertyMetadataInterface $property, ContextInterface $context)
    {
        $visited = false;
        $this->enterScope($data, $property, $context);

        if (!$this->exclusionStrategy->skipProperty($property, $context)) {
            $visited = $this->doVisitObjectProperty(
                $data,
                $this->namingStrategy->convert($property->getName()),
                $property,
                $context
            );
        }

        $this->leaveScope($context);

        return $visited;
    }

    /**
     * {@inheritdoc}
     */
    public function finishVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context)
    {
        $this->leaveScope($context);

        $result = $this->result;
        $this->result = $this->stack->pop();

        if ($this->result === null || $this->stack->isEmpty()) {
            $this->result = $result;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }

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
     * @param ClassMetadataInterface $classMetadata
     *
     * @return mixed
     */
    abstract protected function createResult(ClassMetadataInterface $classMetadata);

    /**
     * @param mixed                             $data
     * @param TypeMetadataInterface|string|null $type
     * @param ContextInterface                  $context
     *
     * @return mixed
     */
    protected function navigate($data, $type, ContextInterface $context)
    {
        return $context->getNavigator()->navigate($data, $type, clone $context);
    }

    /**
     * @param mixed             $data
     * @param MetadataInterface $metadata
     * @param ContextInterface  $context
     */
    private function enterScope($data, MetadataInterface $metadata, ContextInterface $context)
    {
        $context->getDataStack()->push($data);
        $context->getMetadataStack()->push($metadata);
    }

    /**
     * @param ContextInterface $context
     */
    private function leaveScope(ContextInterface $context)
    {
        $context->getDataStack()->pop();
        $context->getMetadataStack()->pop();
    }
}
