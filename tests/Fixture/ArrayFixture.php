<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Fixture;

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ArrayFixture implements FixtureInterface
{
    /**
     * @var string[]
     */
    private $scalars = [];

    /**
     * @var ArrayFixture[]
     */
    private $objects = [];

    /**
     * @var string[]
     */
    private $types = [];

    /**
     * @var string[][]
     */
    private $inceptions = [];

    /**
     * @return bool
     */
    public function hasScalars()
    {
        return !empty($this->scalars);
    }

    /**
     * @Serializer\Type("array<value=string>")
     *
     * @return string[]
     */
    public function getScalars()
    {
        return $this->scalars;
    }

    /**
     * @param string[] $scalars
     */
    public function setScalars(array $scalars)
    {
        foreach ($scalars as $name => $scalar) {
            $this->setScalar($name, $scalar);
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasScalar($name)
    {
        return isset($this->scalars[$name]);
    }

    /**
     * @param int|string $name
     * @param string     $scalar
     */
    public function setScalar($name, $scalar)
    {
        $this->scalars[$name] = $scalar;
    }

    /**
     * @param string $name
     */
    public function removeScalar($name)
    {
        unset($this->scalars[$name]);
    }

    /**
     * @return bool
     */
    public function hasObjects()
    {
        return !empty($this->objects);
    }

    /**
     * @Serializer\Type("array<value=Ivory\Tests\Serializer\Fixture\ArrayFixture>")
     *
     * @return ArrayFixture[]
     */
    public function getObjects()
    {
        return array_values($this->objects);
    }

    /**
     * @param ArrayFixture[] $objects
     */
    public function setObjects(array $objects)
    {
        foreach ($objects as $object) {
            $this->addObject($object);
        }
    }

    /**
     * @param ArrayFixture $object
     *
     * @return bool
     */
    public function hasObject(ArrayFixture $object)
    {
        return in_array($object, $this->objects, true);
    }

    /**
     * @param ArrayFixture $object
     */
    public function addObject(ArrayFixture $object)
    {
        if (!$this->hasObject($object)) {
            $this->objects[] = $object;
        }
    }

    /**
     * @param ArrayFixture $object
     */
    public function removeObject(ArrayFixture $object)
    {
        unset($this->objects[array_search($object, $this->objects, true)]);
    }

    /**
     * @return bool
     */
    public function hasTypes()
    {
        return !empty($this->types);
    }

    /**
     * @Serializer\Type("array<key=int, value=string>")
     *
     * @return string[]
     */
    public function getTypes()
    {
        return array_values($this->types);
    }

    /**
     * @param string[] $types
     */
    public function setTypes($types)
    {
        foreach ($types as $type) {
            $this->addType($type);
        }
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function hasType($type)
    {
        return in_array($type, $this->types, true);
    }

    /**
     * @param string $type
     */
    public function addType($type)
    {
        if (!$this->hasType($type)) {
            $this->types[] = $type;
        }
    }

    /**
     * @param string $type
     */
    public function removeType($type)
    {
        unset($this->types[array_search($type, $this->types, true)]);
    }

    /**
     * @return bool
     */
    public function isInceptions()
    {
        return !empty($this->types);
    }

    /**
     * @Serializer\Type("array<key=string, value=array<key=int, value=string>>")
     *
     * @return string[][]
     */
    public function getInceptions()
    {
        return $this->inceptions;
    }

    /**
     * @param string[][] $inceptions
     */
    public function setInceptions(array $inceptions)
    {
        $this->inceptions = [];

        foreach ($inceptions as $name => $inception) {
            $this->setInception($name, $inception);
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function isInception($name)
    {
        return isset($this->inceptions[$name]);
    }

    /**
     * @param string   $name
     * @param string[] $inception
     */
    public function setInception($name, array $inception)
    {
        $this->inceptions[$name] = $inception;
    }

    /**
     * @param string $name
     */
    public function removeInception($name)
    {
        unset($this->inceptions[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        return [
            'scalars'    => $this->scalars,
            'types'      => $this->types,
            'inceptions' => $this->inceptions,
            'objects'    => array_map(function (FixtureInterface $object) use ($options) {
                return $object->toArray($options);
            }, $this->objects),
        ];
    }
}
