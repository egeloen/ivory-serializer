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
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ExceptionType extends AbstractClassType
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
    protected function serialize($exception, TypeMetadataInterface $type, ContextInterface $context)
    {
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
     * {@inheritdoc}
     */
    protected function deserialize($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        throw new \RuntimeException(sprintf('Deserializing an "Exception" is not supported.'));
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
