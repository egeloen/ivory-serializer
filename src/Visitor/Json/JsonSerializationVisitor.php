<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Visitor\Json;

use Ivory\Serializer\Accessor\AccessorInterface;
use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Visitor\AbstractSerializationVisitor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class JsonSerializationVisitor extends AbstractSerializationVisitor
{
    /**
     * @var int
     */
    private $options;

    /**
     * @param AccessorInterface $accessor
     * @param int               $options
     */
    public function __construct(AccessorInterface $accessor, $options = 0)
    {
        parent::__construct($accessor);

        if (defined('JSON_PRESERVE_ZERO_FRACTION')) {
            $options |= JSON_PRESERVE_ZERO_FRACTION;
        }

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function visitData($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        if ($data === [] && class_exists($type->getName())) {
            $data = (object) $data;
        }

        return parent::visitData($data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function finishVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context)
    {
        if ($this->result === []) {
            $this->result = (object) $this->result;
        }

        return parent::finishVisitingObject($data, $class, $context);
    }

    /**
     * {@inheritdoc}
     */
    protected function encode($data)
    {
        $result = @json_encode($data, $this->options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        return $result;
    }
}
