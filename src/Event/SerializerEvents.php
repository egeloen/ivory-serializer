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

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
final class SerializerEvents
{
    const CLASS_METADATA_LOAD = 'serializer.class_metadata.load';
    const CLASS_METADATA_NOT_FOUND = 'serializer.class_metadata.not_found';

    const PRE_SERIALIZE = 'serializer.pre_serialize';
    const POST_SERIALIZE = 'serializer.post_serialize';

    const PRE_DESERIALIZE = 'serializer.pre_deserialize';
    const POST_DESERIALIZE = 'serializer.post_deserialize';

    private function __construct()
    {
    }
}
