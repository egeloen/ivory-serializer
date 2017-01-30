<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Visitor\Csv;

use Ivory\Serializer\Accessor\AccessorInterface;
use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Visitor\AbstractSerializationVisitor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class CsvSerializationVisitor extends AbstractSerializationVisitor
{
    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var string
     */
    private $enclosure;

    /**
     * @var string
     */
    private $escapeChar;

    /**
     * @var string
     */
    private $keySeparator;

    /**
     * @param AccessorInterface $accessor
     * @param string            $delimiter
     * @param string            $enclosure
     * @param string            $escapeChar
     * @param string            $keySeparator
     */
    public function __construct(
        AccessorInterface $accessor,
        $delimiter = ',',
        $enclosure = '"',
        $escapeChar = '\\',
        $keySeparator = '.'
    ) {
        parent::__construct($accessor);

        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escapeChar = $escapeChar;
        $this->keySeparator = $keySeparator;
    }

    /**
     * {@inheritdoc}
     */
    public function visitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        if ($data === []) {
            return $this->visitData('[]', $type, $context);
        }

        return parent::visitArray($data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function visitBoolean($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $this->visitData($data ? 'true' : 'false', $type, $context);
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

        return $this->visitData($data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    protected function encode($data)
    {
        $resource = fopen('php://temp,', 'w+');
        $headers = null;

        if (!is_array($data)) {
            $data = [[$data]];
        } elseif (!empty($data) && !$this->isIndexedArray($data)) {
            $data = [$data];
        }

        foreach ($data as $value) {
            $result = [];
            $this->flatten($value, $result);

            if ($headers === null) {
                if (!$this->isIndexedArray($result)) {
                    $headers = array_keys($result);
                    fputcsv($resource, $headers, $this->delimiter, $this->enclosure, $this->escapeChar);
                } else {
                    $headers = count($result);
                }
            } elseif (is_array($headers) && $headers !== array_keys($result)) {
                fclose($resource);

                throw new \InvalidArgumentException(sprintf(
                    'The input dimension is not equals for all entries (Expected: %d, got %d).',
                    count($headers),
                    count($result)
                ));
            } elseif ($headers !== count($result)) {
                fclose($resource);

                throw new \InvalidArgumentException(sprintf(
                    'The input dimension is not equals for all entries (Expected: %d, got %d).',
                    $headers,
                    count($result)
                ));
            }

            fputcsv($resource, $result, $this->delimiter, $this->enclosure, $this->escapeChar);
        }

        rewind($resource);
        $result = stream_get_contents($resource);
        fclose($resource);

        return $result;
    }

    /**
     * @param mixed[] $array
     * @param mixed[] $result
     * @param string  $parentKey
     */
    private function flatten(array $array, array &$result, $parentKey = '')
    {
        foreach ($array as $key => $value) {
            $key = $parentKey.$key;

            if ($value === []) {
                $value = null;
            }

            if (is_array($value)) {
                $this->flatten($value, $result, $key.$this->keySeparator);
            } else {
                $result[$key] = is_string($value) ? str_replace(PHP_EOL, '', $value) : $value;
            }
        }
    }

    /**
     * @param mixed[] $array
     *
     * @return bool
     */
    private function isIndexedArray(array $array)
    {
        return ctype_digit(implode('', array_keys($array)));
    }
}
