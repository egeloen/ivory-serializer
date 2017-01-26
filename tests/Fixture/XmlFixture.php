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
class XmlFixture implements FixtureInterface
{
    /**
     * @var string
     */
    public $foo;

    /**
     * @Serializer\XmlAttribute
     *
     * @var string
     */
    public $bar;

    /**
     * @Serializer\XmlCollection
     *
     * @var string[]
     */
    public $list;

    /**
     * @Serializer\XmlCollection(keyAsNode = true)
     *
     * @var string[]
     */
    public $keyAsNode;

    /**
     * @Serializer\XmlCollection(keyAsAttribute = true)
     *
     * @var string[]
     */
    public $keyAsAttribute;

    /**
     * @Serializer\XmlCollection(entry = "item")
     *
     * @var string[]
     */
    public $entry;

    /**
     * @Serializer\XmlCollection(entryAttribute = "name")
     *
     * @var string[]
     */
    public $entryAttribute;

    /**
     * @Serializer\XmlCollection(inline = true, entry = "inline", entryAttribute = "index")
     *
     * @var string[]
     */
    public $inline;

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        return [
            'foo'              => $this->foo,
            'bar'              => $this->bar,
            'list'             => $this->list,
            'key_as_attribute' => $this->keyAsAttribute,
            'key_as_node'      => $this->keyAsNode,
            'entry'            => $this->entry,
            'entry_attribute'  => $this->entryAttribute,
            'inline'           => $this->inline,
        ];
    }
}
