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
use Ivory\Serializer\Exclusion\ExclusionStrategyInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Naming\NamingStrategyInterface;
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
    private $version = '1.0';

    /**
     * @var string
     */
    private $encoding = 'UTF-8';

    /**
     * @var string
     */
    private $root = 'result';

    /**
     * @var string
     */
    private $entry = 'entry';

    /**
     * @param AccessorInterface               $accessor
     * @param ExclusionStrategyInterface|null $exclusionStrategy
     * @param NamingStrategyInterface|null    $namingStrategy
     */
    public function __construct(
        AccessorInterface $accessor,
        ExclusionStrategyInterface $exclusionStrategy = null,
        NamingStrategyInterface $namingStrategy = null
    ) {
        parent::__construct($exclusionStrategy, $namingStrategy);

        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($data)
    {
        $this->stack = new \SplStack();

        return parent::prepare($data);
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
        $this->enterScope($node);

        // FIXME - Detect errors
        $this->navigate($this->accessor->getValue($data, $property->getName()), $property->getType(), $context);

        $this->leaveScope();
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
                $node->setAttribute('key', $key);
            }

            $this->enterScope($node);
            $this->navigate($value, $type->getOption('value'), $context);
            $this->leaveScope();
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
    private function enterScope(\DOMNode $node)
    {
        $this->stack->push($this->node);
        $this->node = $node;
    }

    private function leaveScope()
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
