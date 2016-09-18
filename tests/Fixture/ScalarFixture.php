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
class ScalarFixture implements FixtureInterface
{
    /**
     * @Serializer\Type("bool")
     *
     * @var bool
     */
    public $bool;

    /**
     * @Serializer\Type("int")
     *
     * @var int
     */
    public $int;

    /**
     * @Serializer\Type("float")
     *
     * @var float
     */
    public $float;

    /**
     * @Serializer\Type("string")
     *
     * @var string
     */
    public $string;

    /**
     * @Serializer\Type("Ivory\Tests\Serializer\Fixture\ScalarFixture")
     *
     * @var ScalarFixture|null
     */
    private $type;

    /**
     * @return ScalarFixture|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ScalarFixture|null $type
     */
    public function setType(ScalarFixture $type = null)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        return [
            'bool'   => $this->bool,
            'int'    => $this->int,
            'float'  => $this->float,
            'string' => $this->string,
            'type'   => $this->type !== null ? $this->type->toArray() : null,
        ];
    }
}
