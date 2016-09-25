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
     * @var \DOMNode|null
     */
    private $node;

    /**
     * @var \SplStack
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
     * @param string            $root
     * @param string            $entry
     * @param string            $entryAttribute
     */
    public function __construct(
        AccessorInterface $accessor,
        $version = '1.0',
        $encoding = 'UTF-8',
        $root = 'result',
        $entry = 'entry',
        $entryAttribute = 'key'
    ) {
        $this->accessor = $accessor;
        $this->version = $version;
        $this->encoding = $encoding;
        $this->root = $root;
        $this->entry = $entry;
        $this->entryAttribute = $entryAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($data, ContextInterface $context)
    {
        $this->stack = new \SplStack();
        $this->document = null;
        $this->node = null;

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
    public function visitString($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $document = $this->getDocument();
        $data = (string) $data;

        $node = $this->requireCData($data)
            ? $document->createCDATASection($data)
            : $document->createTextNode($data);

        return $this->visitNode($node);
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
        $node = $this->createNode($name);
        $this->enterNodeScope($node);

        // FIXME - Detect errors
        $this->navigator->navigate(
            $this->accessor->getValue($data, $property->getName()),
            $context,
            $property->getType()
        );

        $this->leaveNodeScope();
        $this->visitNode($node);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doVisitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        $this->getDocument();

        foreach ($data as $key => $value) {
            $node = $this->createNode(is_string($key) ? $key : $this->entry);

            if (is_int($key)) {
                $node->setAttribute($this->entryAttribute, $key);
            }

            $this->enterNodeScope($node);
            $this->navigator->navigate($value, $context, $type->getOption('value'));
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
     * @param \DOMNode $node
     */
    private function enterNodeScope(\DOMNode $node)
    {
        $this->stack->push($this->node);
        $this->node = $node;
    }

    private function leaveNodeScope()
    {
        $this->node = $this->stack->pop();
    }

    /**
     * @param string $data
     *
     * @return bool
     */
    private function requireCData($data)
    {
        return strpos($data, '<') !== false || strpos($data, '>') !== false || strpos($data, '&') !== false;
    }

    /**
     * @return \DOMDocument
     */
    private function getDocument()
    {
        return $this->document !== null ? $this->document : $this->document = $this->createDocument();
    }

    /**
     * @param string $name
     *
     * @return \DOMElement
     */
    private function createNode($name)
    {
        return $this->getDocument()->createElement($name);
    }

    /**
     * @return \DOMDocument
     */
    private function createDocument()
    {
        $document = new \DOMDocument($this->version, $this->encoding);
        $document->formatOutput = true;

        $this->node = $document->createElement($this->root);
        $document->appendChild($this->node);

        return $document;
    }
}
