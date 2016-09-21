<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Visitor\Yaml;

use Ivory\Serializer\Accessor\AccessorInterface;
use Ivory\Serializer\Exclusion\ExclusionStrategyInterface;
use Ivory\Serializer\Naming\NamingStrategyInterface;
use Ivory\Serializer\Visitor\AbstractSerializationVisitor;
use Symfony\Component\Yaml\Yaml;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class YamlSerializationVisitor extends AbstractSerializationVisitor
{
    /**
     * @var int
     */
    private $inline;

    /**
     * @var int
     */
    private $indent;

    /**
     * @var int
     */
    private $options;

    /**
     * @param AccessorInterface               $accessor
     * @param ExclusionStrategyInterface|null $exclusionStrategy
     * @param NamingStrategyInterface|null    $namingStrategy
     * @param int                             $inline
     * @param int                             $indent
     * @param int                             $options
     */
    public function __construct(
        AccessorInterface $accessor,
        ExclusionStrategyInterface $exclusionStrategy = null,
        NamingStrategyInterface $namingStrategy = null,
        $inline = 2,
        $indent = 4,
        $options = 0
    ) {
        parent::__construct($accessor, $exclusionStrategy, $namingStrategy);

        $this->inline = $inline;
        $this->indent = $indent;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function encode($data)
    {
        try {
            return Yaml::dump($data, $this->inline, $this->indent, $this->options);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Unable to serialize data.', 0, $e);
        }
    }
}
