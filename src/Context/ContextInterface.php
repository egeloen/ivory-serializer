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

use Ivory\Serializer\Exclusion\ExclusionStrategyInterface;
use Ivory\Serializer\Mapping\MetadataInterface;
use Ivory\Serializer\Naming\NamingStrategyInterface;
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
     * @return ExclusionStrategyInterface
     */
    public function getExclusionStrategy();

    /**
     * @param ExclusionStrategyInterface $exclusionStrategy
     *
     * @return ContextInterface
     */
    public function setExclusionStrategy(ExclusionStrategyInterface $exclusionStrategy);

    /**
     * @return NamingStrategyInterface
     */
    public function getNamingStrategy();

    /**
     * @param NamingStrategyInterface $namingStrategy
     *
     * @return ContextInterface
     */
    public function setNamingStrategy(NamingStrategyInterface $namingStrategy);

    /**
     * @return \SplStack
     */
    public function getDataStack();

    /**
     * @param \SplStack $dataStack
     *
     * @return ContextInterface
     */
    public function setDataStack(\SplStack $dataStack);

    /**
     * @return \SplStack
     */
    public function getMetadataStack();

    /**
     * @param \SplStack $metadataStack
     *
     * @return ContextInterface
     */
    public function setMetadataStack(\SplStack $metadataStack);

    /**
     * @param mixed             $data
     * @param MetadataInterface $metadata
     */
    public function enterScope($data, MetadataInterface $metadata);

    public function leaveScope();
}
