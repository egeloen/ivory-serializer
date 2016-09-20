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
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractGenericVisitor extends AbstractVisitor
{
    /**
     * @var \SplStack
     */
    private $stack;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * {@inheritdoc}
     */
    public function prepare($data)
    {
        $this->stack = new \SplStack();

        return parent::prepare($data);
    }

    /**
     * {@inheritdoc}
     */
    public function visitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $this->enterScope();
        $result = parent::visitArray($data, $type, $context);
        $this->leaveScope();

        return $this->visitData($result, $type, $context);
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
    public function startVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context)
    {
        if (!parent::startVisitingObject($data, $class, $context)) {
            return false;
        }

        $this->enterScope();
        $this->result = $this->createResult($class->getName());

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function finishVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context)
    {
        parent::finishVisitingObject($data, $class, $context);

        return $this->leaveScope();
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $class
     *
     * @return mixed
     */
    abstract protected function createResult($class);

    /**
     * {@inheritdoc}
     */
    protected function doVisitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $this->result = [];

        foreach ($data as $key => $value) {
            $this->result[$this->navigate($key, $type->getOption('key'), $context)] = $this->navigate(
                $value,
                $type->getOption('value'),
                $context
            );
        }

        return $this->result;
    }

    private function enterScope()
    {
        $this->stack->push($this->result);
    }

    /**
     * @return mixed
     */
    private function leaveScope()
    {
        $result = $this->result;
        $this->result = $this->stack->pop();

        if ($this->result === null) {
            $this->result = $result;
        }

        return $result;
    }
}
