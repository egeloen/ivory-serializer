<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Visitor;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
interface VisitorInterface
{
    /**
     * @param mixed            $data
     * @param ContextInterface $context
     *
     * @return mixed
     */
    public function prepare($data, ContextInterface $context);

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    public function visitArray($data, TypeMetadataInterface $type, ContextInterface $context);

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    public function visitBoolean($data, TypeMetadataInterface $type, ContextInterface $context);

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    public function visitData($data, TypeMetadataInterface $type, ContextInterface $context);

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    public function visitFloat($data, TypeMetadataInterface $type, ContextInterface $context);

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    public function visitInteger($data, TypeMetadataInterface $type, ContextInterface $context);

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    public function visitNull($data, TypeMetadataInterface $type, ContextInterface $context);

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    public function visitResource($data, TypeMetadataInterface $type, ContextInterface $context);

    /**
     * @param mixed                 $data
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     *
     * @return mixed
     */
    public function visitString($data, TypeMetadataInterface $type, ContextInterface $context);

    /**
     * @param mixed                  $data
     * @param ClassMetadataInterface $class
     * @param ContextInterface       $context
     *
     * @return bool
     */
    public function startVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context);

    /**
     * @param mixed                     $data
     * @param PropertyMetadataInterface $property
     * @param ContextInterface          $context
     *
     * @return bool
     */
    public function visitObjectProperty($data, PropertyMetadataInterface $property, ContextInterface $context);

    /**
     * @param mixed                  $data
     * @param ClassMetadataInterface $class
     * @param ContextInterface       $context
     *
     * @return mixed
     */
    public function finishVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context);

    /**
     * @return mixed
     */
    public function getResult();
}
