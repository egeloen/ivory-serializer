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
use Ivory\Serializer\Mapping\ClassMetadataInterface;
use Ivory\Serializer\Mapping\Loader\ClassMetadataLoaderInterface;
use Ivory\Serializer\Mapping\PropertyMetadataInterface;
use Ivory\Tests\Serializer\Fixture\ArrayFixture;
use Ivory\Tests\Serializer\Fixture\AscFixture;
use Ivory\Tests\Serializer\Fixture\DateTimeFixture;
use Ivory\Tests\Serializer\Fixture\DescFixture;
use Ivory\Tests\Serializer\Fixture\ExcludeFixture;
use Ivory\Tests\Serializer\Fixture\ExposeFixture;
use Ivory\Tests\Serializer\Fixture\GroupFixture;
use Ivory\Tests\Serializer\Fixture\MaxDepthFixture;
use Ivory\Tests\Serializer\Fixture\OrderFixture;
use Ivory\Tests\Serializer\Fixture\ScalarFixture;
use Ivory\Tests\Serializer\Fixture\VersionFixture;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractClassMetadataLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassMetadataLoaderInterface
     */
    private $loader;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->setLoader($this->createLoader('mapping'));
    }

    public function testInheritance()
    {
        $this->assertInstanceOf(ClassMetadataLoaderInterface::class,  $this->loader);
    }

    public function testArrayFixture()
    {
        $classMetadata = new ClassMetadata(ArrayFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'scalars'    => ['type' => 'array<value=string>'],
            'objects'    => ['type' => 'array<value='.ArrayFixture::class.'>'],
            'types'      => ['type' => 'array<key=int, value=string>'],
            'inceptions' => ['type' => 'array<key=string, value=array<key=int, value=string>>'],
        ]);
    }

    public function testScalarFixture()
    {
        $classMetadata = new ClassMetadata(ScalarFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'bool'   => ['type' => 'bool'],
            'float'  => ['type' => 'float'],
            'int'    => ['type' => 'int'],
            'string' => ['type' => 'string'],
            'type'   => ['type' => ScalarFixture::class],
        ]);
    }

    public function testDateTimeFixture()
    {
        $classMetadata = new ClassMetadata(DateTimeFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'dateTime'                   => ['type' => 'DateTime'],
            'formattedDateTime'          => ['type' => 'DateTime<format=\'Y-m-d, H:i:s, P\'>'],
            'timeZonedDateTime'          => ['type' => 'DateTime<timezone=\'Europe/Paris\'>'],
            'immutableDateTime'          => ['type' => 'DateTimeImmutable'],
            'formattedImmutableDateTime' => ['type' => 'DateTimeImmutable<format=\'Y-m-d, H:i:s, P\'>'],
            'timeZonedImmutableDateTime' => ['type' => 'DateTimeImmutable<timezone=\'Europe/Paris\'>'],
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

    public function testMaxDepthFixture()
    {
        $classMetadata = new ClassMetadata(MaxDepthFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'parent'         => ['type' => MaxDepthFixture::class, 'max_depth' => 1],
            'children'       => ['type' => 'array<value='.MaxDepthFixture::class.'>', 'max_depth' => 2],
            'orphanChildren' => ['type' => 'array<value='.MaxDepthFixture::class.'>', 'max_depth' => 1],
        ]);
    }

    public function testGroupFixture()
    {
        $classMetadata = new ClassMetadata(GroupFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => ['groups' => ['group1', 'group2']],
            'bar' => ['groups' => ['group1']],
            'baz' => ['groups' => ['group2']],
            'bat' => [],
        ]);
    }

    public function testOrderFixture()
    {
        $classMetadata = new ClassMetadata(OrderFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertSame(['bar', 'foo'], array_keys($classMetadata->getProperties()));
    }

    public function testAscFixture()
    {
        $classMetadata = new ClassMetadata(AscFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertSame(['bar', 'foo'], array_keys($classMetadata->getProperties()));
    }

    public function testDescFixture()
    {
        $classMetadata = new ClassMetadata(DescFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertSame(['foo', 'bar'], array_keys($classMetadata->getProperties()));
    }

    public function testVersionFixture()
    {
        $classMetadata = new ClassMetadata(VersionFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => ['since' => '1.0', 'until' => '2.0'],
            'bar' => ['since' => '1.0'],
            'baz' => ['until' => '2.0'],
            'bat' => [],
        ]);
    }

    public function testUnknownFixture()
    {
        $this->assertFalse($this->loadClassMetadata(new ClassMetadata(\stdClass::class)));
    }

    /**
     * @param string $file
     *
     * @return ClassMetadataLoaderInterface
     */
    abstract protected function createLoader($file);

    /**
     * @param ClassMetadataLoaderInterface $loader
     */
    protected function setLoader(ClassMetadataLoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param ClassMetadataInterface $classMetadata
     *
     * @return bool
     */
    protected function loadClassMetadata(ClassMetadataInterface $classMetadata)
    {
        return $this->loader->loadClassMetadata($classMetadata);
    }

    /**
     * @param ClassMetadataInterface $classMetadata
     * @param mixed[][]              $properties
     */
    protected function assertClassMetadata(ClassMetadataInterface $classMetadata, array $properties)
    {
        foreach ($properties as $property => $data) {
            $this->assertTrue($classMetadata->hasProperty($property));
            $this->assertPropertyMetadata($classMetadata->getProperty($property), $data);
        }
    }

    /**
     * @param PropertyMetadataInterface $propertyMetadata
     * @param mixed[]                   $data
     */
    private function assertPropertyMetadata(PropertyMetadataInterface $propertyMetadata, array $data)
    {
        $this->assertSame(isset($data['type']), $propertyMetadata->hasType(), $propertyMetadata->getName());
        $this->assertSame(
            isset($data['type']) ? $data['type'] : null,
            $propertyMetadata->hasType() ? (string) $propertyMetadata->getType() : null
        );

        $this->assertSame(isset($data['max_depth']), $propertyMetadata->hasMaxDepth());
        $this->assertSame(isset($data['max_depth']) ? $data['max_depth'] : null, $propertyMetadata->getMaxDepth());

        $this->assertSame(isset($data['groups']), $propertyMetadata->hasGroups());
        $this->assertSame(isset($data['groups']) ? $data['groups'] : [], $propertyMetadata->getGroups());
    }
}
