<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Visitor\Xml;

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Type\Type;
use Ivory\Serializer\Visitor\AbstractDeserializationVisitor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class XmlDeserializationVisitor extends AbstractDeserializationVisitor
{
    /**
     * @var string
     */
    private $entry = 'entry';

    /**
     * {@inheritdoc}
     */
    protected function decode($data)
    {
        $internalErrors = libxml_use_internal_errors(true);
        $disableEntityLoader = libxml_disable_entity_loader(true);

        $document = simplexml_load_string($data);

        libxml_use_internal_errors($internalErrors);
        libxml_disable_entity_loader($disableEntityLoader);

        if ($document === false) {
            throw new \InvalidArgumentException(libxml_get_last_error());
        }

        return $document;
    }

    /**
     * {@inheritdoc}
     */
    protected function doVisitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $this->result = [];

        if (isset($data[$this->entry])) {
            $entries = $data[$this->entry];
            $entries = is_array($entries) ? $entries : [$entries];

            foreach ($entries as $key => $value) {
                $this->visitArrayItem($key, $value, $type, $context);
            }
        }

        foreach ($data as $key => $value) {
            if ($key !== $this->entry) {
                $this->visitArrayItem($key, $value, $type, $context);
            }
        }

        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    protected function doVisitObjectProperty(
        $data,
        $name,
        PropertyMetadataInterface $property,
        ContextInterface $context
    ) {
        return parent::doVisitObjectProperty($this->visitNode($data, $property->getType()), $name, $property, $context);
    }

    /**
     * {@inheritdoc}
     */
    protected function navigate($data, $type, ContextInterface $context)
    {
        return parent::navigate($this->visitNode($data, $type), $type, $context);
    }

    /**
     * @param mixed                 $key
     * @param mixed                 $value
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     */
    private function visitArrayItem($key, $value, TypeMetadataInterface $type, ContextInterface $context)
    {
        $key = $this->visitArrayKey($key, $value, $type->getOption('key'), $context);
        $result = $this->navigate($value, $type->getOption('value'), $context);

        if ($key === null) {
            $this->result[] = $result;
        } else {
            $this->result[$key] = $result;
        }
    }

    /**
     * @param mixed                             $key
     * @param mixed                             $value
     * @param TypeMetadataInterface|string|null $type
     * @param ContextInterface                  $context
     *
     * @return mixed
     */
    private function visitArrayKey($key, $value, $type, ContextInterface $context)
    {
        if ($value instanceof \SimpleXMLElement) {
            $key = $value->getName();

            if ($key === $this->entry) {
                $attributes = $value->attributes();
                $key = isset($attributes['key']) ? $attributes['key'] : null;
            }
        } elseif ($key === $this->entry) {
            $key = null;
        }

        return $this->navigate($key, $type, $context);
    }

    /**
     * @param mixed                             $data
     * @param TypeMetadataInterface|string|null $type
     *
     * @return mixed
     */
    private function visitNode($data, $type)
    {
        if (!$data instanceof \SimpleXMLElement) {
            return $data;
        }

        if (count($data)) {
            return (array) $data;
        }

        $data = (string) $data;

        if ($data !== '') {
            return $data;
        }

        if ($type instanceof TypeMetadataInterface) {
            $type = $type->getName();
        }

        if ($type === Type::ARRAY_) {
            return [];
        }
    }
}
