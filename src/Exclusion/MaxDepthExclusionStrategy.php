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
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class MaxDepthExclusionStrategy extends ExclusionStrategy
{
    /**
     * {@inheritdoc}
     */
    public function skipClass(ClassMetadataInterface $class, ContextInterface $context)
    {
        return $this->skip($context);
    }

    /**
     * {@inheritdoc}
     */
    public function skipType(TypeMetadataInterface $type, ContextInterface $context)
    {
        return $this->skip($context);
    }

    /**
     * @param ContextInterface $context
     *
     * @return bool
     */
    private function skip(ContextInterface $context)
    {
        if (!$context->hasMaxDepthEnabled()) {
            return false;
        }

        $metadataStack = $context->getMetadataStack();
        $stackDepth = count($context->getDataStack());
        $depth = 0;

        for ($i = $metadataStack->count() - 1; $i > 0; --$i) {
            $metadata = $metadataStack[$i];

            if ($metadata instanceof TypeMetadataInterface) {
                ++$depth;
            }

            if (!$metadata instanceof PropertyMetadataInterface) {
                continue;
            }

            ++$depth;

            if (!$metadata->hasMaxDepth()) {
                continue;
            }

            if ($stackDepth - $depth > $metadata->getMaxDepth()) {
                return true;
            }
        }

        return false;
    }
}
