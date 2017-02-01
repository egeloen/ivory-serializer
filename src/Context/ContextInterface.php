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
     * @param NavigatorInterface $navigator
     * @param VisitorInterface   $visitor
     * @param int                $direction
     * @param string             $format
     */
    public function initialize(NavigatorInterface $navigator, VisitorInterface $visitor, $direction, $format);

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
     * @return string
     */
    public function getFormat();

    /**
     * @param string $format
     *
     * @return ContextInterface
     */
    public function setFormat($format);

    /**
     * @return bool
     */
    public function isNullIgnored();

    /**
     * @param bool $considerNull
     *
     * @return ContextInterface
     */
    public function setIgnoreNull($considerNull);

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
     * @return mixed[]
     */
    public function getDataStack();

    /**
     * @param mixed[] $dataStack
     *
     * @return ContextInterface
     */
    public function setDataStack(array $dataStack);

    /**
     * @return MetadataInterface[]
     */
    public function getMetadataStack();

    /**
     * @param MetadataInterface[] $metadataStack
     *
     * @return ContextInterface
     */
    public function setMetadataStack(array $metadataStack);

    /**
     * @param mixed             $data
     * @param MetadataInterface $metadata
     *
     * @return ContextInterface
     */
    public function enterScope($data, MetadataInterface $metadata);

    /**
     * @return ContextInterface
     */
    public function leaveScope();

    /**
     * @return bool
     */
    public function hasOptions();

    /**
     * @return mixed[]
     */
    public function getOptions();

    /**
     * @param mixed[] $options
     *
     * @return ContextInterface
     */
    public function setOptions(array $options);

    /**
     * @param mixed[] $options
     *
     * @return ContextInterface
     */
    public function addOptions(array $options);

    /**
     * @param string $option
     *
     * @return bool
     */
    public function hasOption($option);

    /**
     * @param string $option
     *
     * @return mixed
     */
    public function getOption($option);

    /**
     * @param string $option
     * @param mixed  $value
     *
     * @return ContextInterface
     */
    public function setOption($option, $value);

    /**
     * @param string $option
     *
     * @return ContextInterface
     */
    public function removeOption($option);
}
