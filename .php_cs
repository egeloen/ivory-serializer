<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\CS\Config\Config;
use Symfony\CS\Finder;

$finder = Finder::create()
    ->in([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->exclude('tests/Fixture/Resource/config');

return Config::create()
    ->setUsingCache(true)
    ->fixers([
        'align_double_arrow',
        'short_array_syntax',
        'ordered_use',
    ])
    ->finder($finder);
