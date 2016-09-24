<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Navigator;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
interface NavigatorInterface
{
    /**
     * @param mixed                      $data
     * @param ContextInterface           $context
     * @param TypeMetadataInterface|null $type
     *
     * @return mixed
     */
    public function navigate($data, ContextInterface $context, TypeMetadataInterface $type = null);
}
