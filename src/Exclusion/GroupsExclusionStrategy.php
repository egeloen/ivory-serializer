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
class GroupsExclusionStrategy extends ExclusionStrategy
{
    const GROUP_DEFAULT = 'Default';

    /**
     * @var string[]
     */
    private $groups;

    /**
     * @param string[] $groups
     */
    public function __construct(array $groups)
    {
        $this->groups = $groups;
    }

    /**
     * {@inheritdoc}
     */
    public function skipProperty(PropertyMetadataInterface $property, ContextInterface $context)
    {
        if (!$property->hasGroups()) {
            return !in_array(self::GROUP_DEFAULT, $this->groups, true);
        }

        foreach ($this->groups as $group) {
            if ($property->hasGroup($group)) {
                return false;
            }
        }

        return true;
    }
}
