<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Event;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractEvent extends Event
{
    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var TypeMetadataInterface
     */
    protected $type;

    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     */
    public function __construct($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $this->data = $data;
        $this->type = $type;
        $this->context = $context;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return TypeMetadataInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return ContextInterface
     */
    public function getContext()
    {
        return $this->context;
    }
}
