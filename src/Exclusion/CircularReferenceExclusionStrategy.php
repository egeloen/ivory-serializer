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

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class CircularReferenceExclusionStrategy extends ExclusionStrategy
{
    /**
     * @var int
     */
    private $limit;

    /**
     * @param int $limit
     */
    public function __construct($limit = 1)
    {
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function skipClass(ClassMetadataInterface $class, ContextInterface $context)
    {
        $references = [];
        $dataStack = $context->getDataStack();
        $metadataStack = $context->getMetadataStack();

        for ($i = $metadataStack->count() - 1; $i > 0; --$i) {
            $data = $dataStack[$i];
            $metadata = $metadataStack[$i];

            if (!$metadata instanceof ClassMetadataInterface || !is_object($data)) {
                continue;
            }

            $hash = spl_object_hash($data);

            if (!isset($references[$hash])) {
                $references[$hash] = 0;
            }

            foreach ($references as &$reference) {
                if (++$reference >= $this->limit) {
                    return true;
                }
            }
        }

        return false;
    }
}
