<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Accessor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ReflectionAccessor implements AccessorInterface
{
    /**
     * @var \ReflectionProperty[]
     */
    private $properties = [];

    /**
     * @var \ReflectionMethod[]
     */
    private $methods = [];

    /**
     * {@inheritdoc}
     */
    public function getValue($object, $property)
    {
        if (property_exists($object, $property)) {
            return $this->getReflectionProperty($object, $property)->getValue($object);
        }

        return $this->getMethodValue($object, $property);
    }

    /**
     * @param object $object
     * @param string $property
     *
     * @return mixed
     */
    private function getMethodValue($object, $property)
    {
        $methods = [$property];

        if (method_exists($object, $property)) {
            return $this->getReflectionMethod($object, $property)->invoke($object);
        }

        $methodSuffix = ucfirst($property);

        foreach (['get', 'has', 'is'] as $methodPrefix) {
            $methods[] = $method = $methodPrefix.$methodSuffix;

            if (method_exists($object, $method)) {
                return $this->getReflectionMethod($object, $method)->invoke($object);
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'The property "%s" or methods %s don\'t exist on class "%s".',
            $property,
            '"'.implode('", "', $methods).'"',
            get_class($object)
        ));
    }

    /**
     * @param object $object
     * @param string $property
     *
     * @return \ReflectionProperty
     */
    private function getReflectionProperty($object, $property)
    {
        if (isset($this->properties[$key = $this->getCacheKey($object, $property)])) {
            return $this->properties[$key];
        }

        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);

        return $this->properties[$key] = $reflection;
    }

    /**
     * @param object $object
     * @param string $method
     *
     * @return \ReflectionMethod
     */
    private function getReflectionMethod($object, $method)
    {
        if (isset($this->methods[$key = $this->getCacheKey($object, $method)])) {
            return $this->methods[$key];
        }

        $reflection = new \ReflectionMethod($object, $method);
        $reflection->setAccessible(true);

        return $this->methods[$key] = $reflection;
    }

    /**
     * @param object $object
     * @param string $key
     *
     * @return string
     */
    private function getCacheKey($object, $key)
    {
        return get_class($object).'::'.$key;
    }
}
