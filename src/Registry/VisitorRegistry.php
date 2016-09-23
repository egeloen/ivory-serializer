<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Registry;

use Ivory\Serializer\Accessor\ReflectionAccessor;
use Ivory\Serializer\Direction;
use Ivory\Serializer\Format;
use Ivory\Serializer\Instantiator\DoctrineInstantiator;
use Ivory\Serializer\Mutator\ReflectionMutator;
use Ivory\Serializer\Visitor\Json\JsonDeserializationVisitor;
use Ivory\Serializer\Visitor\Json\JsonSerializationVisitor;
use Ivory\Serializer\Visitor\VisitorInterface;
use Ivory\Serializer\Visitor\Xml\XmlDeserializationVisitor;
use Ivory\Serializer\Visitor\Xml\XmlSerializationVisitor;
use Ivory\Serializer\Visitor\Yaml\YamlDeserializationVisitor;
use Ivory\Serializer\Visitor\Yaml\YamlSerializationVisitor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class VisitorRegistry implements VisitorRegistryInterface
{
    /**
     * @var VisitorInterface[][]
     */
    private $visitors = [];

    /**
     * @param VisitorInterface[][] $visitors
     */
    public function __construct(array $visitors = [])
    {
        foreach ($visitors as $direction => $formattedVisitors) {
            foreach ($formattedVisitors as $format => $visitor) {
                $this->registerVisitor($direction, $format, $visitor);
            }
        }
    }

    /**
     * @param VisitorInterface[][] $visitors
     *
     * @return VisitorRegistryInterface
     */
    public static function create(array $visitors = [])
    {
        $instantiator = new DoctrineInstantiator();
        $accessor = new ReflectionAccessor();
        $mutator = new ReflectionMutator();

        return new static(array_replace_recursive([
            Direction::SERIALIZATION => [
                Format::JSON => new JsonSerializationVisitor($accessor),
                Format::XML  => new XmlSerializationVisitor($accessor),
                Format::YAML => new YamlSerializationVisitor($accessor),
            ],
            Direction::DESERIALIZATION => [
                Format::JSON => new JsonDeserializationVisitor($instantiator, $mutator),
                Format::XML  => new XmlDeserializationVisitor($instantiator, $mutator),
                Format::YAML => new YamlDeserializationVisitor($instantiator, $mutator),
            ],
        ], $visitors));
    }

    /**
     * {@inheritdoc}
     */
    public function registerVisitor($direction, $format, VisitorInterface $visitor)
    {
        if (!isset($this->visitors[$direction])) {
            $this->visitors[$direction] = [];
        }

        $this->visitors[$direction][$format] = $visitor;
    }

    /**
     * {@inheritdoc}
     */
    public function getVisitor($direction, $format)
    {
        if (!isset($this->visitors[$direction]) || !isset($this->visitors[$direction][$format])) {
            throw new \InvalidArgumentException(sprintf(
                'The visitor for direction "%s" and format "%s" does not exist.',
                $direction === Direction::SERIALIZATION ? 'serialization' : 'deserialization',
                $format
            ));
        }

        return $this->visitors[$direction][$format];
    }
}
