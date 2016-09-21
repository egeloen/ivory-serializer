<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping\Loader;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractReflectionClassMetadataLoader extends AbstractClassMetadataLoader
{
    /**
     * @param \ReflectionProperty $property
     *
     * @return mixed[]|null
     */
    abstract protected function loadProperty(\ReflectionProperty $property);

    /**
     * {@inheritdoc}
     */
    protected function loadData($class)
    {
        $reflection = new \ReflectionClass($class);
        $result = $this->loadClass($reflection);
        $properties = [];

        foreach ($reflection->getMethods() as $method) {
            if ($method->class !== $class) {
                continue;
            }

            if (($methodName = $this->validateMethod($method->name)) === null) {
                continue;
            }

            $data = $this->loadMethod($method);

            if (is_array($data)) {
                $properties[$methodName] = $data;
            }
        }

        foreach ($reflection->getProperties() as $property) {
            if ($property->class !== $class) {
                continue;
            }

            $data = $this->loadProperty($property);

            if (is_array($data)) {
                $name = $property->getName();

                $properties[$name] = isset($properties[$name])
                    ? array_merge_recursive($properties[$name], $data)
                    : $data;
            }
        }

        if (!empty($properties)) {
            $result['properties'] = $properties;
        }

        if (!empty($result)) {
            return $result;
        }
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return mixed[]
     */
    protected function loadClass(\ReflectionClass $class)
    {
        return [];
    }

    /**
     * @param \ReflectionMethod $method
     *
     * @return mixed[]|null
     */
    protected function loadMethod(\ReflectionMethod $method)
    {
    }

    /**
     * @param string $method
     *
     * @return string|null
     */
    private function validateMethod($method)
    {
        $prefix = substr($method, 0, 3);

        if ($prefix === 'get' || $prefix === 'set' || $prefix === 'has') {
            return lcfirst(substr($method, 3));
        }

        $prefix = substr($prefix, 0, 2);

        if ($prefix === 'is') {
            return lcfirst(substr($method, 2));
        }
    }
}
