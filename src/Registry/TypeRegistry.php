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

use Ivory\Serializer\Type\ArrayType;
use Ivory\Serializer\Type\BooleanType;
use Ivory\Serializer\Type\ClosureType;
use Ivory\Serializer\Type\DateTimeType;
use Ivory\Serializer\Type\ExceptionType;
use Ivory\Serializer\Type\FloatType;
use Ivory\Serializer\Type\IntegerType;
use Ivory\Serializer\Type\NullType;
use Ivory\Serializer\Type\ObjectType;
use Ivory\Serializer\Type\ResourceType;
use Ivory\Serializer\Type\StdClassType;
use Ivory\Serializer\Type\StringType;
use Ivory\Serializer\Type\Type;
use Ivory\Serializer\Type\TypeInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class TypeRegistry implements TypeRegistryInterface
{
    /**
     * @var TypeInterface[]
     */
    private $types = [];

    /**
     * @param TypeInterface[] $types
     */
    public function __construct(array $types = [])
    {
        foreach ($types as $name => $type) {
            $this->registerType($name, $type);
        }
    }

    /**
     * @param TypeInterface[] $types
     *
     * @return TypeRegistryInterface
     */
    public static function create(array $types = [])
    {
        return new static(array_merge([
            Type::ARRAY_    => new ArrayType(),
            Type::BOOL      => $booleanType = new BooleanType(),
            Type::BOOLEAN   => $booleanType,
            Type::CLOSURE   => new ClosureType(),
            Type::DATE_TIME => new DateTimeType(),
            Type::DOUBLE    => $floatType = new FloatType(),
            Type::EXCEPTION => new ExceptionType(),
            Type::FLOAT     => $floatType,
            Type::INT       => $integerType = new IntegerType(),
            Type::INTEGER   => $integerType,
            Type::NULL      => new NullType(),
            Type::NUMERIC   => $floatType,
            Type::OBJECT    => new ObjectType(),
            Type::RESOURCE  => new ResourceType(),
            Type::STD_CLASS => new StdClassType(),
            Type::STRING    => new StringType(),
        ], $types));
    }

    /**
     * {@inheritdoc}
     */
    public function registerType($name, TypeInterface $type)
    {
        $this->types[$name] = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getType($name)
    {
        if (!isset($this->types[$name])) {
            if (!class_exists($name)) {
                throw new \InvalidArgumentException(sprintf('The type "%s" does not exist.', $name));
            }

            if (($type = $this->findClassType($name)) === null) {
                $type = $this->getType(Type::OBJECT);
            }

            $this->registerType($name, $type);
        }

        return $this->types[$name];
    }

    /**
     * @param string $name
     *
     * @return TypeInterface|null
     */
    private function findClassType($name)
    {
        foreach ($this->types as $class => $type) {
            if ((class_exists($class) || interface_exists($class)) && is_a($name, $class, true)) {
                return $type;
            }
        }
    }
}
