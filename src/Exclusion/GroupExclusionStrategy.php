<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Exclusion;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class GroupExclusionStrategy extends ExclusionStrategy
{
    /**
     * {@inheritdoc}
     */
    public function skipProperty(PropertyMetadataInterface $property, ContextInterface $context)
    {
        if (!$context->hasGroups()) {
            return false;
        }

        foreach ($context->getGroups() as $group) {
            if ($property->hasGroup($group)) {
                return false;
            }
        }

        return true;
    }
}
