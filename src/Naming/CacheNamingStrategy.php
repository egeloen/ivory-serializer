<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Naming;

use Psr\Cache\CacheItemPoolInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class CacheNamingStrategy extends AbstractNamingStrategy
{
    /**
     * @var NamingStrategyInterface
     */
    private $strategy;

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @param NamingStrategyInterface $strategy
     * @param CacheItemPoolInterface  $cache
     */
    public function __construct(NamingStrategyInterface $strategy, CacheItemPoolInterface $cache)
    {
        $this->strategy = $strategy;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    protected function doConvert($name)
    {
        $item = $this->cache->getItem($name);

        if ($item->isHit()) {
            return $item->get();
        }

        $result = $this->strategy->convert($name);
        $this->cache->save($item->set($result));

        return $result;
    }
}
