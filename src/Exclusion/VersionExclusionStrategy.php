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
class VersionExclusionStrategy extends ExclusionStrategy
{
    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function skipProperty(PropertyMetadataInterface $property, ContextInterface $context)
    {
        if ($property->hasSinceVersion() && version_compare($property->getSinceVersion(), $this->version, '>')) {
            return true;
        }

        if ($property->hasUntilVersion() && version_compare($property->getUntilVersion(), $this->version, '<')) {
            return true;
        }

        return false;
    }
}
