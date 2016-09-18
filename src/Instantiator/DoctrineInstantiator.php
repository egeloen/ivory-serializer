<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Instantiator;

use Doctrine\Instantiator\Instantiator;
use Doctrine\Instantiator\InstantiatorInterface as DoctrineInstantiatorInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class DoctrineInstantiator implements InstantiatorInterface
{
    /**
     * @var DoctrineInstantiatorInterface
     */
    private $instantiator;

    /**
     * @param DoctrineInstantiatorInterface|null $instantiator
     */
    public function __construct(DoctrineInstantiatorInterface $instantiator = null)
    {
        $this->instantiator = $instantiator ?: new Instantiator();
    }

    /**
     * {@inheritdoc}
     */
    public function instantiate($class)
    {
        return $this->instantiator->instantiate($class);
    }
}
