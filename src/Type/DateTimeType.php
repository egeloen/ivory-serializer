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
     * @param string      $format
     * @param string|null $timeZone
     */
    public function __construct($format = \DateTime::RFC3339, $timeZone = null)
    {
        $this->format = $format;
        $this->timeZone = $timeZone ?: date_default_timezone_get();
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

        $result = $data->format($format = $type->getOption('format', $this->format));

        if ($result === false) {
            throw new \InvalidArgumentException(sprintf('The date format "%s" is not valid.', $format));
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function deserialize($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $class = $type->getName();

        if (!method_exists($class, 'createFromFormat')) {
            throw new \InvalidArgumentException(sprintf(
                'The method "%s" does not exist on "%s".',
                'createFromFormat',
                $class
            ));
        }

        if (!method_exists($class, 'getLastErrors')) {
            throw new \InvalidArgumentException(sprintf(
                'The method "%s" does not exist on "%s".',
                'getLastErrors',
                $class
            ));
        }

        $result = $class::createFromFormat(
            $format = $type->getOption('format', $this->format),
            $data,
            $timezone = new \DateTimeZone($type->getOption('timezone', $this->timeZone))
        );

        $errors = $class::getLastErrors();

        if (!empty($errors['warnings']) || !empty($errors['errors'])) {
            throw new \InvalidArgumentException(sprintf(
                'The date "%s" with format "%s" and timezone "%s" is not valid.',
                $data,
                $format,
                $timezone->getName()
            ));
        }

        return $result;
    }
}
