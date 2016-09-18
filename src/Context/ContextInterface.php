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
interface ContextInterface
{
    /**
     * @return NavigatorInterface
     */
    public function getNavigator();

    /**
     * @param NavigatorInterface $navigator
     *
     * @return ContextInterface
     */
    public function setNavigator(NavigatorInterface $navigator);

    /**
     * @return VisitorInterface
     */
    public function getVisitor();

    /**
     * @param VisitorInterface $visitor
     *
     * @return ContextInterface
     */
    public function setVisitor(VisitorInterface $visitor);

    /**
     * @return int
     */
    public function getDirection();

    /**
     * @param int $direction
     *
     * @return ContextInterface
     */
    public function setDirection($direction);

    /**
     * @return bool
     */
    public function hasVersion();

    /**
     * @return string|null
     */
    public function getVersion();

    /**
     * @param string|null $version
     *
     * @return ContextInterface
     */
    public function setVersion($version);

    /**
     * @return bool
     */
    public function hasGroups();

    /**
     * @return string[]
     */
    public function getGroups();

    /**
     * @param string[] $groups
     *
     * @return ContextInterface
     */
    public function setGroups(array $groups);

    /**
     * @param string[] $groups
     *
     * @return ContextInterface
     */
    public function addGroups(array $groups);

    /**
     * @param string $group
     *
     * @return bool
     */
    public function hasGroup($group);

    /**
     * @param string $group
     *
     * @return ContextInterface
     */
    public function addGroup($group);

    /**
     * @param string $group
     *
     * @return ContextInterface
     */
    public function removeGroup($group);

    /**
     * @return \SplStack
     */
    public function getDataStack();

    /**
     * @return \SplStack
     */
    public function getMetadataStack();
}
