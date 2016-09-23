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
     * {@inheritdoc}
     */
    public function getValue($object, $property)
    {
        try {
            return $this->getPropertyValue($object, $property);
        } catch (\ReflectionException $e) {
            return $this->getMethodValue($object, $property);
        }
    }

    /**
     * @param object $object
     * @param string $property
     *
     * @return mixed
     */
    private function getPropertyValue($object, $property)
    {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);

        return $reflection->getValue($object);
    }

    /**
     * @param object $object
     * @param string $property
     *
     * @return mixed
     */
    private function getMethodValue($object, $property)
    {
        $methods = [];
        $suffix = ucfirst($property);

        foreach (['get', 'has', 'is'] as $prefix) {
            try {
                $reflection = new \ReflectionMethod($object, $methods[] = $prefix.$suffix);
                $reflection->setAccessible(true);

                return $reflection->invoke($object);
            } catch (\ReflectionException $e) {
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'The property "%s" or methods %s don\'t exist.',
            $property,
            '"'.implode('", "', $methods).'"'
        ));
    }
}
