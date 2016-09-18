<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping\Factory;

use Psr\Cache\CacheItemPoolInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class CacheClassMetadataFactory implements ClassMetadataFactoryInterface
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    private $factory;

    /**
     * @var CacheItemPoolInterface
     */
    private $pool;

    /**
     * @param ClassMetadataFactoryInterface $factory
     * @param CacheItemPoolInterface        $pool
     */
    public function __construct(ClassMetadataFactoryInterface $factory, CacheItemPoolInterface $pool)
    {
        $this->factory = $factory;
        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassMetadata($class)
    {
        $item = $this->pool->getItem(strtr($class, '\\', '_'));

        if ($item->isHit()) {
            return $item->get();
        }

        $classMetadata = $this->factory->getClassMetadata($class);
        $this->pool->save($item->set($classMetadata));

        return $classMetadata;
    }
}
