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
interface TypeMetadataInterface extends MetadataInterface
{
    /**
     * @return bool
     */
    public function hasOptions();

    /**
     * @return mixed[]
     */
    public function getOptions();

    /**
     * @param mixed[] $options
     */
    public function setOptions(array $options);

    /**
     * @param string $option
     *
     * @return bool
     */
    public function hasOption($option);

    /**
     * @param string $option
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($option, $default = null);

    /**
     * @param string $option
     * @param mixed  $value
     */
    public function setOption($option, $value);

    /**
     * @param string $option
     */
    public function removeOption($option);
}
