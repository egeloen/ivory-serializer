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

use Ivory\Serializer\Exclusion\ExclusionStrategyInterface;
use Ivory\Serializer\Instantiator\InstantiatorInterface;
use Ivory\Serializer\Mutator\MutatorInterface;
use Ivory\Serializer\Naming\NamingStrategyInterface;
use Ivory\Serializer\Visitor\AbstractDeserializationVisitor;
use Symfony\Component\Yaml\Yaml;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class YamlDeserializationVisitor extends AbstractDeserializationVisitor
{
    /**
     * @var int
     */
    private $options;

    /**
     * @param InstantiatorInterface           $instantiator
     * @param MutatorInterface                $mutator
     * @param ExclusionStrategyInterface|null $exclusionStrategy
     * @param NamingStrategyInterface|null    $namingStrategy
     * @param int                             $options
     */
    public function __construct(
        InstantiatorInterface $instantiator,
        MutatorInterface $mutator,
        ExclusionStrategyInterface $exclusionStrategy = null,
        NamingStrategyInterface $namingStrategy = null,
        $options = 0
    ) {
        parent::__construct($instantiator, $mutator);

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function decode($data)
    {
        try {
            return Yaml::parse($data, $this->options);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Unable to deserialize data.', 0, $e);
        }
    }
}
