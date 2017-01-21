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
 * @Serializer\XmlRoot("xml")
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class XmlValueFixture implements FixtureInterface
{
    /**
     * @Serializer\XmlAttribute
     *
     * @var string
     */
    public $foo;

    /**
     * @Serializer\XmlValue
     *
     * @var string
     */
    public $bar;

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,
        ];
    }
}
