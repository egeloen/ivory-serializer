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

        if (isset($xml['exclusion-policy'])) {
            $data['exclusion_policy'] = (string) $xml['exclusion-policy'];
        }

        foreach ($xml->class as $class) {
            $properties = [];

            foreach ($class->property as $property) {
                $properties[(string) $property['name']] = $this->loadProperty($property);
            }

            $data[(string) $class['name']] = ['properties' => $properties];
        }

        return $data;
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

        if (isset($element['exclude']) && $element['exclude'] === 'true') {
            $property['exclude'] = true;
        }

        if (isset($element['expose']) && $element['expose'] === 'true') {
            $property['expose'] = true;
        }

        if (isset($element['since'])) {
            $property['since'] = (string) $element['since'];
        }

        if (isset($element['until'])) {
            $property['until'] = (string) $element['until'];
        }

        if (isset($element['max-depth'])) {
            $property['max_depth'] = (string) $element['max-depth'];
        }

        foreach ($element->group as $group) {
            $property['groups'][] = (string) $group;
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
        $data = trim(file_get_contents($file));

        if (empty($data)) {
            throw new \InvalidArgumentException();
        }

        $internalErrors = libxml_use_internal_errors();
        $disableEntities = libxml_disable_entity_loader();

        $this->setState(true, true);

        $document = new \DOMDocument();
        $document->validateOnParse = true;

        if (!$document->loadXML($data, LIBXML_NONET | LIBXML_COMPACT)) {
            $this->setState($internalErrors, $disableEntities);

            throw new \InvalidArgumentException();
        }

        $document->normalizeDocument();

        foreach ($document->childNodes as $child) {
            if ($child->nodeType === XML_DOCUMENT_TYPE_NODE) {
                throw new \InvalidArgumentException('The document type is not allowed.');
            }
        }

        $this->validateDocument($document, $internalErrors, $disableEntities);
        $this->setState($internalErrors, $disableEntities);

        return $document;
    }

    /**
     * @param \DOMDocument $document
     * @param bool         $internalErrors
     * @param bool         $disableEntities
     */
    private function validateDocument(\DOMDocument $document, $internalErrors, $disableEntities)
    {
        if (@$document->schemaValidateSource(file_get_contents(__DIR__.'/../Resource/mapping.xsd'))) {
            return;
        }

        $errors = [];

        foreach (libxml_get_errors() as $error) {
            $errors[] = sprintf('[%s %s] %s (in %s - line %d, column %d)',
                LIBXML_ERR_WARNING === $error->level ? 'WARNING' : 'ERROR',
                $error->code,
                trim($error->message),
                $error->file ?: 'n/a',
                $error->line,
                $error->column
            );
        }

        $this->setState($internalErrors, $disableEntities);

        throw new \InvalidArgumentException(implode(PHP_EOL, $errors));
    }

    /**
     * @param bool $internalErrors
     * @param bool $disableEntities
     */
    private function setState($internalErrors, $disableEntities)
    {
        libxml_use_internal_errors($internalErrors);
        libxml_disable_entity_loader($disableEntities);
        libxml_clear_errors();
    }
}
