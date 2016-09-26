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
use Ivory\Serializer\Instantiator\InstantiatorInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadata;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Mutator\MutatorInterface;
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
    private $entry;

    /**
     * @var string
     */
    private $entryAttribute;

    /**
     * @param InstantiatorInterface $instantiator
     * @param MutatorInterface      $mutator
     * @param string                $entry
     * @param string                $entryAttribute
     */
    public function __construct(
        InstantiatorInterface $instantiator,
        MutatorInterface $mutator,
        $entry = 'entry',
        $entryAttribute = 'key'
    ) {
        parent::__construct($instantiator, $mutator);

        $this->entry = $entry;
        $this->entryAttribute = $entryAttribute;
    }

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
        $data = $this->visitNode($data, new TypeMetadata(Type::ARRAY_));

        if (!isset($data[$name])) {
            return false;
        }

        $data[$name] = $this->visitNode($data[$name], $property->getType());

        return parent::doVisitObjectProperty($data, $name, $property, $context);
    }

    /**
     * @param mixed                 $key
     * @param mixed                 $value
     * @param TypeMetadataInterface $type
     * @param ContextInterface      $context
     */
    private function visitArrayItem($key, $value, TypeMetadataInterface $type, ContextInterface $context)
    {
        $result = $this->navigator->navigate(
            $this->visitNode($value, $valueType = $type->getOption('value')),
            $context,
            $valueType
        );

        if ($result === null && $context->isNullIgnored()) {
            return;
        }

        if ($value instanceof \SimpleXMLElement) {
            $key = $value->getName();

            if ($key === $this->entry) {
                $attributes = $value->attributes();
                $key = isset($attributes[$this->entryAttribute]) ? $attributes[$this->entryAttribute] : null;
            }
        } elseif ($key === $this->entry) {
            $key = null;
        }

        $key = $this->navigator->navigate(
            $this->visitNode($key, $keyType = $type->getOption('key')),
            $context,
            $keyType
        );

        if ($key === null) {
            $this->result[] = $result;
        } else {
            $this->result[$key] = $result;
        }
    }

    /**
     * @param mixed                      $data
     * @param TypeMetadataInterface|null $type
     *
     * @return mixed
     */
    private function visitNode($data, TypeMetadataInterface $type = null)
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

        if ($type !== null && $type->getName() === Type::ARRAY_) {
            return [];
        }
    }
}
