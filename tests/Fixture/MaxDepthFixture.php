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
class MaxDepthFixture implements FixtureInterface
{
    /**
     * @Serializer\Type("Ivory\Tests\Serializer\Fixture\MaxDepthFixture")
     * @Serializer\MaxDepth(1)
     *
     * @var MaxDepthFixture
     */
    private $parent;

    /**
     * @Serializer\Type("array<value=Ivory\Tests\Serializer\Fixture\MaxDepthFixture>")
     * @Serializer\MaxDepth(2)
     *
     * @var MaxDepthFixture[]
     */
    private $children = [];

    /**
     * @Serializer\Type("array<value=Ivory\Tests\Serializer\Fixture\MaxDepthFixture>")
     * @Serializer\MaxDepth(1)
     *
     * @var MaxDepthFixture[]
     */
    public $orphanChildren = [];

    /**
     * @return bool
     */
    public function hasParent()
    {
        return $this->parent !== null;
    }

    /**
     * @return MaxDepthFixture|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param MaxDepthFixture|null $parent
     */
    public function setParent(MaxDepthFixture $parent = null)
    {
        $previousParent = $this->parent;
        $this->parent = $parent;

        if ($previousParent !== null) {
            $previousParent->removeChild($this);
        }

        if ($parent !== null && !$parent->hasChild($this)) {
            $parent->addChild($this);
        }
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * @return MaxDepthFixture[]
     */
    public function getChildren()
    {
        return array_values($this->children);
    }

    /**
     * @param MaxDepthFixture[] $children
     */
    public function setChildren(array $children)
    {
        $this->children = [];

        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    /**
     * @param MaxDepthFixture $child
     *
     * @return bool
     */
    public function hasChild(MaxDepthFixture $child)
    {
        return in_array($child, $this->children, true);
    }

    /**
     * @param MaxDepthFixture $child
     */
    public function addChild(MaxDepthFixture $child)
    {
        if (!$this->hasChild($child)) {
            $this->children[] = $child;
        }

        if ($child->getParent() !== $this) {
            $child->setParent($this);
        }
    }

    /**
     * @param MaxDepthFixture $child
     */
    public function removeChild(MaxDepthFixture $child)
    {
        unset($this->children[array_search($child, $this->children, true)]);

        if ($child->getParent() === $this) {
            $child->setParent(null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        if (!isset($options['depth'])) {
            $options['depth'] = 0;
        }

        ++$options['depth'];

        $miner = function (FixtureInterface $fixture) use ($options) {
            ++$options['depth'];

            return $fixture->toArray($options);
        };

        return [
            'parent'         => $this->parent !== null && $options['depth'] < 1 ? $this->parent->toArray($options) : null,
            'children'       => array_map($miner, $options['depth'] < 2 ? $this->children : []),
            'orphanChildren' => array_map($miner, $options['depth'] < 1 ? $this->orphanChildren : []),
        ];
    }
}
