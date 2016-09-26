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
     * @var mixed[]
     */
    private $stack;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * {@inheritdoc}
     */
    public function prepare($data, ContextInterface $context)
    {
        $this->stack = [];
        $this->result = null;

        return parent::prepare($data, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function visitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $result = [];

        if (!empty($data)) {
            $this->enterScope();
            $result = parent::visitArray($data, $type, $context);
            $this->leaveScope();
        }

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

        $keyType = $type->getOption('key');
        $valueType = $type->getOption('value');
        $ignoreNull = $context->isNullIgnored();

        foreach ($data as $key => $value) {
            $value = $this->navigator->navigate($value, $context, $valueType);

            if ($value === null && $ignoreNull) {
                continue;
            }

            $key = $this->navigator->navigate($key, $context, $keyType);
            $this->result[$key] = $value;
        }

        return $this->result;
    }

    private function enterScope()
    {
        $this->stack[] = $this->result;
    }

    /**
     * @return mixed
     */
    private function leaveScope()
    {
        $result = $this->result;
        $this->result = array_pop($this->stack);

        if ($this->result === null) {
            $this->result = $result;
        }

        return $result;
    }
}
