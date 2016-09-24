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

use Ivory\Serializer\Instantiator\InstantiatorInterface;
use Ivory\Serializer\Mutator\MutatorInterface;
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
     * @param InstantiatorInterface $instantiator
     * @param MutatorInterface      $mutator
     * @param int                   $options
     */
    public function __construct(InstantiatorInterface $instantiator, MutatorInterface $mutator, $options = 0)
    {
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
