<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping\Loader;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class XmlClassMetadataLoader extends AbstractFileClassMetadataLoader
{
    /**
     * {@inheritdoc}
     */
    protected function loadFile($file)
    {
        $data = [];
        $xml = simplexml_import_dom($this->loadDocument($file));

        foreach ($xml->class as $class) {
            $data[(string) $class['name']] = $this->loadClass($class);
        }

        return $data;
    }

    /**
     * @param \SimpleXMLElement $class
     *
     * @return mixed[]
     */
    private function loadClass(\SimpleXMLElement $class)
    {
        $definition = [];

        if (isset($class['exclusion-policy'])) {
            $definition['exclusion_policy'] = (string) $class['exclusion-policy'];
        }

        if (isset($class['order'])) {
            $definition['order'] = (string) $class['order'];
        }

        if (isset($class['readable'])) {
            $definition['readable'] = (string) $class['readable'] === 'true';
        }

        if (isset($class['writable'])) {
            $definition['writable'] = (string) $class['writable'] === 'true';
        }

        if (isset($class['xml-root'])) {
            $definition['xml_root'] = (string) $class['xml-root'];
        }

        $properties = [];

        foreach ($class->property as $property) {
            $properties[(string) $property['name']] = $this->loadProperty($property);
        }

        $definition['properties'] = $properties;

        return $definition;
    }

    /**
     * @param \SimpleXMLElement $element
     *
     * @return mixed[]
     */
    private function loadProperty(\SimpleXMLElement $element)
    {
        $property = [];

        if (isset($element['alias'])) {
            $property['alias'] = (string) $element['alias'];
        }

        if (isset($element['type'])) {
            $property['type'] = (string) $element['type'];
        }

        if (isset($element['exclude'])) {
            $property['exclude'] = (string) $element['exclude'] === 'true';
        }

        if (isset($element['expose'])) {
            $property['expose'] = (string) $element['expose'] === 'true';
        }

        if (isset($element['accessor'])) {
            $property['accessor'] = (string) $element['accessor'];
        }

        if (isset($element['readable'])) {
            $property['readable'] = (string) $element['readable'] === 'true';
        }

        if (isset($element['writable'])) {
            $property['writable'] = (string) $element['writable'] === 'true';
        }

        if (isset($element['mutator'])) {
            $property['mutator'] = (string) $element['mutator'];
        }

        if (isset($element['since'])) {
            $property['since'] = (string) $element['since'];
        }

        if (isset($element['until'])) {
            $property['until'] = (string) $element['until'];
        }

        if (isset($element['max-depth'])) {
            $property['max_depth'] = (int) $element['max-depth'];
        }

        foreach ($element->group as $group) {
            $property['groups'][] = (string) $group;
        }

        if (isset($element['xml-attribute'])) {
            $property['xml_attribute'] = (string) $element['xml-attribute'] === 'true';
        }

        if (isset($element['xml-value'])) {
            $property['xml_value'] = (string) $element['xml-value'] === 'true';
        }

        if (isset($element['xml-inline'])) {
            $property['xml_inline'] = (string) $element['xml-inline'] === 'true';
        }

        if (isset($element['xml-entry'])) {
            $property['xml_entry'] = (string) $element['xml-entry'];
        }

        if (isset($element['xml-entry-attribute'])) {
            $property['xml_entry_attribute'] = (string) $element['xml-entry-attribute'];
        }

        if (isset($element['xml-key-as-attribute'])) {
            $property['xml_key_as_attribute'] = (string) $element['xml-key-as-attribute'] === 'true';
        }

        if (isset($element['xml-key-as-node'])) {
            $property['xml_key_as_node'] = (string) $element['xml-key-as-node'] === 'true';
        }

        return $property;
    }

    /**
     * @param string $file
     *
     * @return \DOMDocument
     */
    private function loadDocument($file)
    {
        $data = trim(@file_get_contents($file));

        if (empty($data)) {
            throw new \InvalidArgumentException(sprintf('The XML mapping file "%s" is not valid.', $file));
        }

        $internalErrors = libxml_use_internal_errors();
        $disableEntities = libxml_disable_entity_loader();

        $this->setLibXmlState(true, true);

        $document = new \DOMDocument();
        $document->validateOnParse = true;

        if (!@$document->loadXML($data, LIBXML_NONET | LIBXML_COMPACT)) {
            throw $this->createException($file, $internalErrors, $disableEntities);
        }

        $document->normalizeDocument();

        if (!@$document->schemaValidateSource(file_get_contents(__DIR__.'/../Resource/mapping.xsd'))) {
            throw $this->createException($file, $internalErrors, $disableEntities);
        }

        foreach ($document->childNodes as $child) {
            if ($child->nodeType === XML_DOCUMENT_TYPE_NODE) {
                throw new \InvalidArgumentException('The document type is not allowed.');
            }
        }

        $this->setLibXmlState($internalErrors, $disableEntities);

        return $document;
    }

    /**
     * @param string $file
     * @param bool   $internalErrors
     * @param bool   $disableEntities
     *
     * @return \InvalidArgumentException
     */
    private function createException($file, $internalErrors, $disableEntities)
    {
        $errors = [];

        foreach (libxml_get_errors() as $error) {
            $errors[] = sprintf('[%s %s] %s (in %s - line %d, column %d)',
                $error->level === LIBXML_ERR_WARNING ? 'WARNING' : 'ERROR',
                $error->code,
                trim($error->message),
                $file,
                $error->line,
                $error->column
            );
        }

        $this->setLibXmlState($internalErrors, $disableEntities);

        return new \InvalidArgumentException(implode(PHP_EOL, $errors));
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
