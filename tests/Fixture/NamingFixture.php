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
class NamingFixture implements FixtureInterface
{
    /**
     * @var string
     */
    public $fooBar;

    /**
     * @var string
     */
    public $baz_bat;

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        return [
            'fooBar'  => $this->fooBar,
            'baz_bat' => $this->baz_bat,
        ];
    }
}
