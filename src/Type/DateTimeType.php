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
class DateTimeType extends AbstractClassType
{
    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $timeZone;

    /**
     * @param string $format
     * @param string $timeZone
     */
    public function __construct($format = \DateTime::RFC3339, $timeZone = 'UTC')
    {
        $this->format = $format;
        $this->timeZone = $timeZone;
    }

    /**
     * {@inheritdoc}
     */
    protected function serialize($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $class = $type->getName();

        if (!$data instanceof $class) {
            throw new \InvalidArgumentException(sprintf(
                'Expected a "%s", got "%s".',
                $class,
                is_object($data) ? get_class($data) : gettype($data)
            ));
        }

        // FIXME - Detect errors
        return $data->format($type->getOption('format', $this->format));
    }

    /**
     * {@inheritdoc}
     */
    protected function deserialize($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $class = $type->getName();

        if (!method_exists($class, $method = 'createFromFormat')) {
            throw new \InvalidArgumentException(sprintf('The method "%s" does not exist on "%s".', $method, $class));
        }

        // FIXME - Detect errors
        return $class::createFromFormat(
            $type->getOption('format', $this->format),
            $data,
            new \DateTimeZone($type->getOption('timezone', $this->timeZone))
        );
    }
}
