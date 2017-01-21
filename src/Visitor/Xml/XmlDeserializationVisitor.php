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
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Mutator\MutatorInterface;
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
        $internalErrors = libxml_use_internal_errors();
        $disableEntityLoader = libxml_disable_entity_loader();

        $this->setLibXmlState(true, true);
        $document = simplexml_load_string($data);

        if ($document === false) {
            $errors = [];

            foreach (libxml_get_errors() as $error) {
                $errors[] = sprintf('[%s %s] %s (in %s - line %d, column %d)',
                    $error->level === LIBXML_ERR_WARNING ? 'WARNING' : 'ERROR',
                    $error->code,
                    trim($error->message),
                    $error->file ?: 'n/a',
                    $error->line,
                    $error->column
                );
            }

            $this->setLibXmlState($internalErrors, $disableEntityLoader);

            throw new \InvalidArgumentException(implode(PHP_EOL, $errors));
        }

        $this->setLibXmlState($internalErrors, $disableEntityLoader);

        return $document;
    }

    /**
     * {@inheritdoc}
     */
    protected function doVisitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $this->result = [];

        $entry = $this->entry;
        $entryAttribute = $this->entryAttribute;
        $keyAsAttribute = false;
        $inline = false;

        $metadataStack = $context->getMetadataStack();
        $metadataIndex = count($metadataStack) - 2;
        $metadata = isset($metadataStack[$metadataIndex]) ? $metadataStack[$metadataIndex] : null;

        if ($metadata instanceof PropertyMetadataInterface) {
            $inline = $metadata->isXmlInline();

            if ($metadata->hasXmlEntry()) {
                $entry = $metadata->getXmlEntry();
            }

            if ($metadata->hasXmlEntryAttribute()) {
                $entryAttribute = $metadata->getXmlEntryAttribute();
            }

            if ($metadata->hasXmlKeyAsAttribute()) {
                $keyAsAttribute = $metadata->useXmlKeyAsAttribute();
            }
        }

        $keyType = $type->getOption('key');
        $valueType = $type->getOption('value');

        if ($data instanceof \SimpleXMLElement && !$inline) {
            $data = $data->children();
        }

        foreach ($data as $key => $value) {
            $result = $value;
            $isElement = $value instanceof \SimpleXMLElement;

            if ($isElement && $valueType === null) {
                $result = $this->visitNode($value, $entry);
            }

            $result = $this->navigator->navigate($result, $context, $valueType);

            if ($result === null && $context->isNullIgnored()) {
                continue;
            }

            if ($key === $entry) {
                $key = null;
            }

            if ($isElement && ($keyAsAttribute || $key === null)) {
                $attributes = $value->attributes();
                $key = isset($attributes[$entryAttribute]) ? $this->visitNode($attributes[$entryAttribute]) : null;
            }

            $key = $this->navigator->navigate($key, $context, $keyType);

            if ($key === null) {
                $this->result[] = $result;
            } else {
                $this->result[$key] = $result;
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
        if ($property->isXmlInline() && $property->useXmlKeyAsNode()) {
            return false;
        }

        if ($property->isXmlAttribute()) {
            return parent::doVisitObjectProperty($data->attributes(), $name, $property, $context);
        }

        if ($property->isXmlValue()) {
            return parent::doVisitObjectProperty([$name => $data], $name, $property, $context);
        }

        $key = $name;

        if ($property->isXmlInline()) {
            $key = $property->hasXmlEntry() ? $property->getXmlEntry() : $this->entry;
        }

        if (!isset($data->$key)) {
            return false;
        }

        $data = $data->$key;

        if ($data->count() === 1 && (string) $data === '') {
            return false;
        }

        return parent::doVisitObjectProperty([$name => $data], $name, $property, $context);
    }

    /**
     * @param \SimpleXMLElement $data
     * @param string|null       $entry
     *
     * @return mixed
     */
    private function visitNode(\SimpleXMLElement $data, $entry = null)
    {
        if ($data->count() === 0) {
            $data = (string) $data;

            return $data !== '' ? $data : null;
        }

        $result = [];
        $entry = $entry ?: $this->entry;

        foreach ($data as $value) {
            $key = $value->getName();

            if ($key === $entry) {
                $result[] = $value;
            } elseif (isset($result[$key])) {
                if (!is_array($result[$key])) {
                    $result[$key] = [$result[$key]];
                }

                $result[$key][] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param bool $internalErrors
     * @param bool $disableEntities
     */
    private function setLibXmlState($internalErrors, $disableEntities)
    {
        libxml_use_internal_errors($internalErrors);
        libxml_disable_entity_loader($disableEntities);
        libxml_clear_errors();
    }
}
