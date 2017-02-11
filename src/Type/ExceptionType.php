<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Type;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Direction;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ExceptionType implements TypeInterface
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function convert($exception, TypeMetadataInterface $type, ContextInterface $context)
    {
        if ($context->getDirection() === Direction::DESERIALIZATION) {
            throw new \RuntimeException(sprintf('De-serializing an "Exception" is not supported.'));
        }

        $result = [
            'code'    => 500,
            'message' => 'Internal Server Error',
        ];

        if ($this->debug) {
            $result['exception'] = $this->serializeException($exception);
        }

        return $context->getVisitor()->visitArray($result, $type, $context);
    }

    /**
     * @param \Exception $exception
     *
     * @return mixed[]
     */
    private function serializeException(\Exception $exception)
    {
        $result = [
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'trace'   => $exception->getTraceAsString(),
        ];

        if ($exception->getPrevious() !== null) {
            $result['previous'] = $this->serializeException($exception->getPrevious());
        }

        return $result;
    }
}
