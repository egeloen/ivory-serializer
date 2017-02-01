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

use Ivory\Serializer\Exclusion\ExclusionStrategy;
use Ivory\Serializer\Exclusion\ExclusionStrategyInterface;
use Ivory\Serializer\Mapping\MetadataInterface;
use Ivory\Serializer\Naming\IdenticalNamingStrategy;
use Ivory\Serializer\Naming\NamingStrategyInterface;
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
     * @var string
     */
    private $format;

    /**
     * @var bool
     */
    private $ignoreNull = false;

    /**
     * @var ExclusionStrategyInterface
     */
    private $exclusionStrategy;

    /**
     * @var NamingStrategyInterface
     */
    private $namingStrategy;

    /**
     * @var mixed[]
     */
    private $dataStack;

    /**
     * @var MetadataInterface[]
     */
    private $metadataStack;

    /**
     * @var mixed[]
     */
    private $options = [];

    /**
     * @param ExclusionStrategyInterface|null $exclusionStrategy
     * @param NamingStrategyInterface|null    $namingStrategy
     */
    public function __construct(
        ExclusionStrategyInterface $exclusionStrategy = null,
        NamingStrategyInterface $namingStrategy = null
    ) {
        $this->exclusionStrategy = $exclusionStrategy ?: new ExclusionStrategy();
        $this->namingStrategy = $namingStrategy ?: new IdenticalNamingStrategy();
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(NavigatorInterface $navigator, VisitorInterface $visitor, $direction, $format)
    {
        $this
            ->setNavigator($navigator)
            ->setVisitor($visitor)
            ->setDirection($direction)
            ->setFormat($format)
            ->setDataStack([])
            ->setMetadataStack([]);
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
     * {@inheritdoc}
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isNullIgnored()
    {
        return $this->ignoreNull;
    }

    /**
     * {@inheritdoc}
     */
    public function setIgnoreNull($ignoreNull)
    {
        $this->ignoreNull = $ignoreNull;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExclusionStrategy()
    {
        return $this->exclusionStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function setExclusionStrategy(ExclusionStrategyInterface $exclusionStrategy)
    {
        $this->exclusionStrategy = $exclusionStrategy;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamingStrategy()
    {
        return $this->namingStrategy;
    }

    /**
     * {@inheritdoc}
     */
    public function setNamingStrategy(NamingStrategyInterface $namingStrategy)
    {
        $this->namingStrategy = $namingStrategy;

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
    public function setDataStack(array $dataStack)
    {
        $this->dataStack = $dataStack;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataStack()
    {
        return $this->metadataStack;
    }

    /**
     * {@inheritdoc}
     */
    public function setMetadataStack(array $metadataStack)
    {
        $this->metadataStack = $metadataStack;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function enterScope($data, MetadataInterface $metadata)
    {
        $this->dataStack[] = $data;
        $this->metadataStack[] = $metadata;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveScope()
    {
        array_pop($this->dataStack);
        array_pop($this->metadataStack);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $this->options = [];
        $this->addOptions($options);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($option)
    {
        return array_key_exists($option, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($option)
    {
        return $this->hasOption($option) ? $this->options[$option] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeOption($option)
    {
        unset($this->options[$option]);

        return $this;
    }
}
