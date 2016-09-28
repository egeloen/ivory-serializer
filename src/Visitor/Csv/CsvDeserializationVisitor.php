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

use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Instantiator\InstantiatorInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Mutator\MutatorInterface;
use Ivory\Serializer\Visitor\AbstractDeserializationVisitor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class CsvDeserializationVisitor extends AbstractDeserializationVisitor
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
     * @param InstantiatorInterface $instantiator
     * @param MutatorInterface      $mutator
     * @param string                $delimiter
     * @param string                $enclosure
     * @param string                $escapeChar
     * @param string                $keySeparator
     */
    public function __construct(
        InstantiatorInterface $instantiator,
        MutatorInterface $mutator,
        $delimiter = ',',
        $enclosure = '"',
        $escapeChar = '\\',
        $keySeparator = '.'
    ) {
        parent::__construct($instantiator, $mutator);

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
        return parent::visitArray($data === '[]' ? [] : $data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    protected function decode($data)
    {
        $result = $first = [];
        $headers = null;

        $resource = fopen('php://temp', 'r+');
        fwrite($resource, $data);
        rewind($resource);

        while (($fields = fgetcsv($resource, 0, $this->delimiter, $this->enclosure, $this->escapeChar)) !== false) {
            if ($fields === [null]) {
                continue;
            }

            $fieldsCount = count($fields);

            if ($headers === null) {
                $first = $fields;
                $headersCount = $fieldsCount;

                $headers = array_map(function ($value) {
                    return explode($this->keySeparator, $value);
                }, $fields);

                continue;
            }

            if ($fieldsCount !== $headersCount) {
                throw new \InvalidArgumentException(sprintf(
                    'The input dimension is not equals for all entries (Expected: %d, got %d).',
                    $headersCount,
                    $fieldsCount
                ));
            }

            foreach ($fields as $key => $value) {
                $this->expand($headers[$key], $value, $result);
            }
        }

        fclose($resource);

        if (!empty($result)) {
            return $result;
        }

        if (empty($first)) {
            return;
        }

        if (count($first) === 1) {
            return reset($first);
        }

        return $first;
    }

    /**
     * @param string[] $paths
     * @param mixed    $data
     * @param mixed[]  $result
     */
    private function expand(array $paths, $data, array &$result)
    {
        $path = array_shift($paths);

        if (empty($paths)) {
            $result[$path] = $data !== '' ? $data : null;
        } else {
            if (!isset($result[$path])) {
                $result[$path] = [];
            }

            $this->expand($paths, $data, $result[$path]);
        }
    }
}
