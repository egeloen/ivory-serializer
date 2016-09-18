<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping\Loader;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class YamlClassMetadataLoader extends AbstractFileClassMetadataLoader
{
    /**
     * {@inheritdoc}
     */
    protected function loadFile($file)
    {
        try {
            return Yaml::parse(file_get_contents($file));
        } catch (ParseException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
