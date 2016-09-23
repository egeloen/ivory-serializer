<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class TypeMetadata implements TypeMetadataInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed[]
     */
    private $options = [];

    /**
     * @param string  $name
     * @param mixed[] $options
     */
    public function __construct($name, array $options = [])
    {
        $this->setName($name);
        $this->setOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function hasOptions()
    {
        return !empty($this->options);
    }

    /**
     * @return mixed[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed[] $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }
    }

    /**
     * @param string $option
     *
     * @return bool
     */
    public function hasOption($option)
    {
        return isset($this->options[$option]);
    }

    /**
     * @param string $option
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($option, $default = null)
    {
        return $this->hasOption($option) ? $this->options[$option] : $default;
    }

    /**
     * @param string $option
     * @param mixed  $value
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
    }

    /**
     * @param string $option
     */
    public function removeOption($option)
    {
        unset($this->options[$option]);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->name,
            $this->options,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->name,
            $this->options
        ) = unserialize($serialized);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $name = (string) $this->getName();

        if (!$this->hasOptions()) {
            return $name;
        }

        $options = $this->getOptions();

        array_walk($options, function (&$value, $option) {
            if (is_string($value)) {
                $value = '\''.$value.'\'';
            }

            $value = $option.'='.$value;
        });

        return $name.'<'.implode(', ', $options).'>';
    }
}
