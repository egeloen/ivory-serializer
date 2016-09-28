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
     * @var int
     */
    private $circularReferenceLimit;

    /**
     * @param int $circularReferenceLimit
     */
    public function __construct($circularReferenceLimit = 1)
    {
        $this->circularReferenceLimit = $circularReferenceLimit;
    }

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
        $dataStack = $context->getDataStack();
        $metadataStack = $context->getMetadataStack();
        $references = [];
        $depth = 0;

        for ($index = count($metadataStack) - 1; $index >= 0; --$index) {
            $metadata = $metadataStack[$index];
            $data = $dataStack[$index];

            if ($metadata instanceof ClassMetadataInterface) {
                $hash = spl_object_hash($data);

                if (!isset($references[$hash])) {
                    $references[$hash] = 0;
                }

                if (++$references[$hash] > $this->circularReferenceLimit) {
                    return true;
                }
            }

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

            if ($depth > $metadata->getMaxDepth()) {
                return true;
            }
        }

        return false;
    }
}
