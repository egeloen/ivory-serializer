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
use Ivory\Serializer\Mapping\Loader\ReflectionClassMetadataLoader;
use Ivory\Tests\Serializer\Fixture\AccessorFixture;
use Ivory\Tests\Serializer\Fixture\ArrayFixture;
use Ivory\Tests\Serializer\Fixture\DateTimeFixture;
use Ivory\Tests\Serializer\Fixture\ExcludeFixture;
use Ivory\Tests\Serializer\Fixture\ExposeFixture;
use Ivory\Tests\Serializer\Fixture\GroupFixture;
use Ivory\Tests\Serializer\Fixture\MaxDepthFixture;
use Ivory\Tests\Serializer\Fixture\MutatorFixture;
use Ivory\Tests\Serializer\Fixture\ScalarFixture;
use Ivory\Tests\Serializer\Fixture\VersionFixture;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ReflectionClassMetadataLoaderTest extends AbstractReflectionClassMetadataLoaderTest
{
    public function testArrayFixture()
    {
        $classMetadata = new ClassMetadata(ArrayFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'scalars'    => [],
            'objects'    => [],
            'types'      => [],
            'inceptions' => [],
        ]);
    }

    public function testScalarFixture()
    {
        $classMetadata = new ClassMetadata(ScalarFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'bool'   => [],
            'float'  => [],
            'int'    => [],
            'string' => [],
            'type'   => [],
        ]);
    }

    public function testDateTimeFixture()
    {
        $classMetadata = new ClassMetadata(DateTimeFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'dateTime'                   => [],
            'formattedDateTime'          => [],
            'timeZonedDateTime'          => [],
            'immutableDateTime'          => [],
            'formattedImmutableDateTime' => [],
            'timeZonedImmutableDateTime' => [],
        ]);
    }

    public function testExcludeFixture()
    {
        $classMetadata = new ClassMetadata(ExcludeFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'bar' => [],
        ]);
    }

    public function testExposeFixture()
    {
        $classMetadata = new ClassMetadata(ExposeFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => [],
        ]);
    }

    public function testAccessorFixture()
    {
        $classMetadata = new ClassMetadata(AccessorFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'name' => [],
        ]);
    }

    public function testMutatorFixture()
    {
        $classMetadata = new ClassMetadata(MutatorFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'name' => [],
        ]);
    }

    public function testMaxDepthFixture()
    {
        $classMetadata = new ClassMetadata(MaxDepthFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'parent'         => [],
            'children'       => [],
            'orphanChildren' => [],
        ]);
    }

    public function testGroupFixture()
    {
        $classMetadata = new ClassMetadata(GroupFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => [],
            'bar' => [],
            'baz' => [],
            'bat' => [],
        ]);
    }

    public function testVersionFixture()
    {
        $classMetadata = new ClassMetadata(VersionFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => [],
            'bar' => [],
            'baz' => [],
            'bat' => [],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function createLoader($file)
    {
        return new ReflectionClassMetadataLoader();
    }
}
