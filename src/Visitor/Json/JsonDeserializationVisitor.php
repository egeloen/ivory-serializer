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

use Ivory\Serializer\Instantiator\InstantiatorInterface;
use Ivory\Serializer\Mutator\MutatorInterface;
use Ivory\Serializer\Visitor\AbstractDeserializationVisitor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class JsonDeserializationVisitor extends AbstractDeserializationVisitor
{
    /**
     * @var int
     */
    private $maxDepth;

    /**
     * @var int
     */
    private $options;

    /**
     * @param InstantiatorInterface $instantiator
     * @param MutatorInterface      $mutator
     * @param int                   $maxDepth
     * @param int                   $options
     */
    public function __construct(
        InstantiatorInterface $instantiator,
        MutatorInterface $mutator,
        $maxDepth = 512,
        $options = 0
    ) {
        parent::__construct($instantiator, $mutator);

        $this->maxDepth = $maxDepth;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function decode($data)
    {
        $result = @json_decode($data, true, $this->maxDepth, $this->options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        return $result;
    }
}
