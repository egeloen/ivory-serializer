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

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class JsonClassMetadataLoader extends AbstractFileClassMetadataLoader
{
    /**
     * {@inheritdoc}
     */
    protected function loadFile($file)
    {
        $data = @json_decode(@file_get_contents($file), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        if (!is_array($data)) {
            throw new \InvalidArgumentException(sprintf('The json mapping file "%s" is not valid.', $file));
        }

        return $data;
    }
}
