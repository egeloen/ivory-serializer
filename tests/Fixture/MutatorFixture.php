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
class MutatorFixture implements FixtureInterface
{
    /**
     * @Serializer\Mutator("setName")
     *
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->setRawName(trim($name));
    }

    /**
     * @param string $name
     */
    public function setRawName($name)
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
