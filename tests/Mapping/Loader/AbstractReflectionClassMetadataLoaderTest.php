<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Mapping\Loader;

use Ivory\Serializer\Mapping\ClassMetadata;
use Ivory\Tests\Serializer\Fixture\ArrayFixture;
use Ivory\Tests\Serializer\Fixture\DateTimeFixture;
use Ivory\Tests\Serializer\Fixture\ExcludeFixture;
use Ivory\Tests\Serializer\Fixture\ExposeFixture;
use Ivory\Tests\Serializer\Fixture\GroupFixture;
use Ivory\Tests\Serializer\Fixture\MaxDepthFixture;
use Ivory\Tests\Serializer\Fixture\VersionFixture;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractReflectionClassMetadataLoaderTest extends AbstractClassMetadataLoaderTest
{
    public function testArrayFixture()
    {
        $classMetadata = new ClassMetadata(ArrayFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'scalars'    => ['type' => 'array'],
            'objects'    => ['type' => 'array'],
            'types'      => ['type' => 'array'],
            'inceptions' => ['type' => 'array'],
        ]);
    }

    public function testDateTimeFixture()
    {
        $classMetadata = new ClassMetadata(DateTimeFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'dateTime'                   => ['type' => 'DateTime'],
            'formattedDateTime'          => ['type' => 'DateTime'],
            'timeZonedDateTime'          => ['type' => 'DateTime'],
            'immutableDateTime'          => ['type' => 'DateTimeImmutable'],
            'formattedImmutableDateTime' => ['type' => 'DateTimeImmutable'],
            'timeZonedImmutableDateTime' => ['type' => 'DateTimeImmutable'],
        ]);
    }

    public function testExcludeFixture()
    {
        $classMetadata = new ClassMetadata(ExcludeFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'bar' => ['type' => 'string'],
        ]);
    }

    public function testExposeFixture()
    {
        $classMetadata = new ClassMetadata(ExposeFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => ['type' => 'string'],
        ]);
    }

    public function testMaxDepthFixture()
    {
        $classMetadata = new ClassMetadata(MaxDepthFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'parent'         => ['type' => MaxDepthFixture::class],
            'children'       => ['type' => 'array'],
            'orphanChildren' => ['type' => 'array'],
        ]);
    }

    public function testGroupFixture()
    {
        $classMetadata = new ClassMetadata(GroupFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => ['type' => 'string'],
            'bar' => ['type' => 'string'],
            'baz' => ['type' => 'string'],
            'bat' => ['type' => 'string'],
        ]);
    }

    public function testVersionFixture()
    {
        $classMetadata = new ClassMetadata(VersionFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => ['type' => 'string'],
            'bar' => ['type' => 'string'],
            'baz' => ['type' => 'string'],
            'bat' => ['type' => 'string'],
        ]);
    }
}
