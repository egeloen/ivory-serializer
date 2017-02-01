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

use Ivory\Serializer\Accessor\AccessorInterface;
use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Visitor\AbstractVisitor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class XmlSerializationVisitor extends AbstractVisitor
{
    /**
     * @var AccessorInterface
     */
    private $accessor;

    /**
     * @var \DOMDocument|null
     */
    private $document;

    /**
     * @var \DOMElement|null
     */
    private $node;

    /**
     * @var \DOMElement[]
     */
    private $stack;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $encoding;

    /**
     * @var bool
     */
    private $formatOutput;

    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $entry;

    /**
     * @var string
     */
    private $entryAttribute;

    /**
     * @param AccessorInterface $accessor
     * @param string            $version
     * @param string            $encoding
     * @param bool              $formatOutput
     * @param string            $root
     * @param string            $entry
     * @param string            $entryAttribute
     */
    public function __construct(
        AccessorInterface $accessor,
        $version = '1.0',
        $encoding = 'UTF-8',
        $formatOutput = true,
        $root = 'result',
        $entry = 'entry',
        $entryAttribute = 'key'
    ) {
        $this->accessor = $accessor;
        $this->version = $version;
        $this->encoding = $encoding;
        $this->formatOutput = $formatOutput;
        $this->root = $root;
        $this->entry = $entry;
        $this->entryAttribute = $entryAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($data, ContextInterface $context)
    {
        $this->document = null;
        $this->node = null;
        $this->stack = [];

        return parent::prepare($data, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function visitBoolean($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $this->visitText($data ? 'true' : 'false');
    }

    /**
     * {@inheritdoc}
     */
    public function visitData($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $this->visitText($data);
    }

    /**
     * {@inheritdoc}
     */
    public function visitFloat($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $data = (string) $data;

        if (strpos($data, '.') === false) {
            $data .= '.0';
        }

        return $this->visitText($data);
    }

    /**
     * {@inheritdoc}
     */
    public function visitString($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $document = $this->getDocument();
        $data = (string) $data;

        $node = strpos($data, '<') !== false || strpos($data, '>') !== false || strpos($data, '&') !== false
            ? $document->createCDATASection($data)
            : $document->createTextNode($data);

        return $this->visitNode($node);
    }

    /**
     * {@inheritdoc}
     */
    public function startVisitingObject($data, ClassMetadataInterface $class, ContextInterface $context)
    {
        $result = parent::startVisitingObject($data, $class, $context);

        if ($result && $class->hasXmlRoot()) {
            $this->getDocument($class->getXmlRoot());
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        $document = $this->getDocument();

        if ($document->formatOutput) {
            $document->loadXML($document->saveXML());
        }

        return $document->saveXML();
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
        if (!$property->isReadable()) {
            return false;
        }

        $value = $this->accessor->getValue(
            $data,
            $property->hasAccessor() ? $property->getAccessor() : $property->getName()
        );

        if ($value === null && $context->isNullIgnored()) {
            return false;
        }

        $node = $this->createNode($name);
        $this->enterNodeScope($node);
        $this->navigator->navigate($value, $context, $property->getType());
        $this->leaveNodeScope();

        if ($property->isXmlAttribute()) {
            $this->node->setAttribute($name, $node->nodeValue);
        } elseif ($property->isXmlValue()) {
            $this->visitNode($node->firstChild);
        } elseif ($property->isXmlInline()) {
            $children = $node->childNodes;
            $count = $children->length;

            for ($index = 0; $index < $count; ++$index) {
                $this->visitNode($children->item(0));
            }
        } else {
            $this->visitNode($node);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doVisitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $entry = $this->entry;
        $entryAttribute = $this->entryAttribute;
        $keyAsAttribute = false;
        $keyAsNode = true;

        $metadataStack = $context->getMetadataStack();
        $metadataIndex = count($metadataStack) - 2;
        $metadata = isset($metadataStack[$metadataIndex]) ? $metadataStack[$metadataIndex] : null;

        if ($metadata instanceof PropertyMetadataInterface) {
            if ($metadata->hasXmlEntry()) {
                $entry = $metadata->getXmlEntry();
            }

            if ($metadata->hasXmlEntryAttribute()) {
                $entryAttribute = $metadata->getXmlEntryAttribute();
            }

            if ($metadata->hasXmlKeyAsAttribute()) {
                $keyAsAttribute = $metadata->useXmlKeyAsAttribute();
            }

            if ($metadata->hasXmlKeyAsNode()) {
                $keyAsNode = $metadata->useXmlKeyAsNode();
            }
        }

        $valueType = $type->getOption('value');
        $ignoreNull = $context->isNullIgnored();

        $this->getDocument();

        foreach ($data as $key => $value) {
            if ($value === null && $ignoreNull) {
                continue;
            }

            $node = $this->createNode($keyAsNode ? $key : $entry, $entry, $entryAttribute);

            if ($keyAsAttribute) {
                $node->setAttribute($entryAttribute, $key);
            }

            $this->enterNodeScope($node);
            $this->navigator->navigate($value, $context, $valueType);
            $this->leaveNodeScope();
            $this->visitNode($node);
        }
    }

    /**
     * {@inheritdoc}
     */
    private function visitText($data)
    {
        return $this->visitNode($this->getDocument()->createTextNode((string) $data));
    }

    /**
     * @param \DOMNode $node
     *
     * @return \DOMNode
     */
    private function visitNode(\DOMNode $node)
    {
        if ($this->node !== $node) {
            $this->node->appendChild($node);
        }

        return $node;
    }

    /**
     * @param \DOMElement $node
     */
    private function enterNodeScope(\DOMElement $node)
    {
        $this->stack[] = $this->node;
        $this->node = $node;
    }

    private function leaveNodeScope()
    {
        $this->node = array_pop($this->stack);
    }

    /**
     * @param string|null $root
     *
     * @return \DOMDocument
     */
    private function getDocument($root = null)
    {
        return $this->document !== null ? $this->document : $this->document = $this->createDocument($root);
    }

    /**
     * @param string      $name
     * @param string|null $entry
     * @param string|null $entryAttribute
     *
     * @return \DOMElement
     */
    private function createNode($name, $entry = null, $entryAttribute = null)
    {
        $document = $this->getDocument();

        try {
            $node = $document->createElement($name);
        } catch (\DOMException $e) {
            $node = $document->createElement($entry ?: $this->entry);

            if (is_string($name)) {
                $node->setAttribute($entryAttribute ?: $this->entryAttribute, $name);
            }
        }

        return $node;
    }

    /**
     * @param string|null $root
     *
     * @return \DOMDocument
     */
    private function createDocument($root = null)
    {
        $document = new \DOMDocument($this->version, $this->encoding);
        $document->formatOutput = $this->formatOutput;

        $this->node = $document->createElement($root ?: $this->root);
        $document->appendChild($this->node);

        return $document;
    }
}
