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
     * @param AccessorInterface $accessor
     * @param int               $inline
     * @param int               $indent
     * @param int               $options
     */
    public function __construct(AccessorInterface $accessor, $inline = 2, $indent = 4, $options = 0)
    {
        parent::__construct($accessor);

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
