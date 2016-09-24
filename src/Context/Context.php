<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Context;

use Ivory\Serializer\Navigator\NavigatorInterface;
use Ivory\Serializer\Visitor\VisitorInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Context implements ContextInterface
{
    /**
     * @var NavigatorInterface
     */
    private $navigator;

    /**
     * @var VisitorInterface
     */
    private $visitor;

    /**
     * @var int
     */
    private $direction;

    /**
     * @var bool
     */
    private $maxDepth = false;

    /**
     * @var string|null
     */
    private $version;

    /**
     * @var string[]
     */
    private $groups = [];

    /**
     * @var \SplStack
     */
    private $dataStack;

    /**
     * @var \SplStack
     */
    private $metadataStack;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->dataStack = new \SplStack();
        $this->metadataStack = new \SplStack();
    }

    /**
     * {@inheritdoc}
     */
    public function getNavigator()
    {
        return $this->navigator;
    }

    /**
     * {@inheritdoc}
     */
    public function setNavigator(NavigatorInterface $navigator)
    {
        $this->navigator = $navigator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * {@inheritdoc}
     */
    public function setVisitor(VisitorInterface $visitor)
    {
        $this->visitor = $visitor;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * {@inheritdoc}
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasMaxDepthEnabled()
    {
        return $this->maxDepth;
    }

    /**
     * {@inheritdoc}
     */
    public function enableMaxDepth($enable = true)
    {
        $this->maxDepth = $enable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasVersion()
    {
        return $this->version !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasGroups()
    {
        return !empty($this->groups);
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        return array_values($this->groups);
    }

    /**
     * {@inheritdoc}
     */
    public function setGroups(array $groups)
    {
        $this->groups = [];
        $this->addGroups($groups);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addGroups(array $groups)
    {
        foreach ($groups as $group) {
            $this->addGroup($group);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasGroup($group)
    {
        return in_array($group, $this->groups, true);
    }

    /**
     * {@inheritdoc}
     */
    public function addGroup($group)
    {
        if (!$this->hasGroup($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeGroup($group)
    {
        unset($this->groups[array_search($group, $this->groups, true)]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataStack()
    {
        return $this->dataStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataStack()
    {
        return $this->metadataStack;
    }
}
