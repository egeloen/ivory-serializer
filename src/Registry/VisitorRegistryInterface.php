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

use Ivory\Serializer\Visitor\VisitorInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
interface VisitorRegistryInterface
{
    /**
     * @param int              $direction
     * @param string           $format
     * @param VisitorInterface $visitor
     */
    public function registerVisitor($direction, $format, VisitorInterface $visitor);

    /**
     * @param int    $direction
     * @param string $format
     *
     * @return VisitorInterface
     */
    public function getVisitor($direction, $format);
}
