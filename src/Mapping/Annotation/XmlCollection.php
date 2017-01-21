<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Mapping\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class XmlCollection
{
    /**
     * @var string|null
     */
    private $entry;

    /**
     * @var string|null
     */
    private $entryAttribute;

    /**
     * @var bool|null
     */
    private $keyAsAttribute;

    /**
     * @var bool|null
     */
    private $keyAsNode;

    /**
     * @var bool|null
     */
    private $inline;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data)
    {
        if (isset($data['entry'])) {
            $this->entry = $data['entry'];
        }

        if (isset($data['entry_attribute'])) {
            $this->entryAttribute = $data['entry_attribute'];
        }

        if (isset($data['key_as_attribute'])) {
            $this->keyAsAttribute = $data['key_as_attribute'];
        }

        if (isset($data['key_as_node'])) {
            $this->keyAsNode = $data['key_as_node'];
        }

        if (isset($data['inline'])) {
            $this->inline = $data['inline'];
        }
    }

    /**
     * @return bool
     */
    public function hasEntry()
    {
        return $this->entry !== null;
    }

    /**
     * @return string|null
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * @return bool
     */
    public function hasEntryAttribute()
    {
        return $this->entryAttribute !== null;
    }

    /**
     * @return string|null
     */
    public function getEntryAttribute()
    {
        return $this->entryAttribute;
    }

    /**
     * @return bool
     */
    public function hasKeyAsAttribute()
    {
        return $this->keyAsAttribute !== null;
    }

    /**
     * @return bool|null
     */
    public function useKeyAsAttribute()
    {
        return $this->keyAsAttribute;
    }

    /**
     * @return bool
     */
    public function hasKeyAsNode()
    {
        return $this->keyAsNode !== null;
    }

    /**
     * @return bool|null
     */
    public function useKeyAsNode()
    {
        return $this->keyAsNode;
    }

    /**
     * @return bool
     */
    public function hasInline()
    {
        return $this->inline !== null;
    }

    /**
     * @return bool|null
     */
    public function isInline()
    {
        return $this->inline;
    }
}
