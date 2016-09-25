<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Fixture;

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class AccessorFixture implements FixtureInterface
{
    /**
     * @Serializer\Accessor("getName")
     *
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return trim($this->name);
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        return ['name' => trim($this->name)];
    }
}
