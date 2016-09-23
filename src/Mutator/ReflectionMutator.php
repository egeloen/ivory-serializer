<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mutator;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ReflectionMutator implements MutatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function setValue($object, $property, $value)
    {
        try {
            return $this->setPropertyValue($object, $property, $value);
        } catch (\ReflectionException $e) {
            $this->setMethodValue($object, $property, $value);
        }
    }

    /**
     * @param object $object
     * @param string $property
     * @param mixed  $value
     */
    private function setPropertyValue($object, $property, $value)
    {
        $reflection = new \ReflectionProperty($object, $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }

    /**
     * @param object $object
     * @param string $property
     * @param mixed  $value
     */
    private function setMethodValue($object, $property, $value)
    {
        $methods = [];
        $suffix = ucfirst($property);

        foreach (['get', 'has', 'is'] as $prefix) {
            try {
                $reflection = new \ReflectionMethod($object, $methods[] = $prefix.$suffix);
                $reflection->setAccessible(true);
                $reflection->invoke($object, $value);

                return;
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
