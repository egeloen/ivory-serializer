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
use Ivory\Tests\Serializer\Fixture\AccessorFixture;
use Ivory\Tests\Serializer\Fixture\ArrayFixture;
use Ivory\Tests\Serializer\Fixture\AscFixture;
use Ivory\Tests\Serializer\Fixture\DateTimeFixture;
use Ivory\Tests\Serializer\Fixture\DescFixture;
use Ivory\Tests\Serializer\Fixture\ExcludeFixture;
use Ivory\Tests\Serializer\Fixture\ExposeFixture;
use Ivory\Tests\Serializer\Fixture\GroupFixture;
use Ivory\Tests\Serializer\Fixture\MaxDepthFixture;
use Ivory\Tests\Serializer\Fixture\MutatorFixture;
use Ivory\Tests\Serializer\Fixture\OrderFixture;
use Ivory\Tests\Serializer\Fixture\ReadableClassFixture;
use Ivory\Tests\Serializer\Fixture\ReadableFixture;
use Ivory\Tests\Serializer\Fixture\ScalarFixture;
use Ivory\Tests\Serializer\Fixture\VersionFixture;
use Ivory\Tests\Serializer\Fixture\WritableClassFixture;
use Ivory\Tests\Serializer\Fixture\WritableFixture;
use Ivory\Tests\Serializer\Fixture\XmlFixture;
use Ivory\Tests\Serializer\Fixture\XmlValueFixture;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
abstract class AbstractClassMetadataLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassMetadataLoaderInterface
     */
    protected $loader;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->loader = $this->createLoader('mapping');
    }

    public function testInheritance()
    {
        $this->assertInstanceOf(ClassMetadataLoaderInterface::class, $this->loader);
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
            'bool'   => ['type' => 'bool', 'alias' => 'boolean'],
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

    public function testReadableFixture()
    {
        $classMetadata = new ClassMetadata(ReadableFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => ['readable' => false],
            'bar' => [],
        ]);
    }

    public function testReadableClassFixture()
    {
        $classMetadata = new ClassMetadata(ReadableClassFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => ['readable' => true],
            'bar' => ['readable' => false],
        ]);
    }

    public function testWritableFixture()
    {
        $classMetadata = new ClassMetadata(WritableFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => ['writable' => false],
            'bar' => [],
        ]);
    }

    public function testWritableClassFixture()
    {
        $classMetadata = new ClassMetadata(WritableClassFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => ['writable' => true],
            'bar' => ['writable' => false],
        ]);
    }

    public function testAccessorFixture()
    {
        $classMetadata = new ClassMetadata(AccessorFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'name' => ['accessor' => 'getName'],
        ]);
    }

    public function testMutatorFixture()
    {
        $classMetadata = new ClassMetadata(MutatorFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'name' => ['mutator' => 'setName'],
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

    public function testXmlFixture()
    {
        $classMetadata = new ClassMetadata(XmlFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo'            => [],
            'bar'            => ['xml_attribute' => true],
            'list'           => [],
            'keyAsAttribute' => ['xml_key_as_attribute' => true],
            'keyAsNode'      => ['xml_key_as_node' => true],
            'entry'          => ['xml_entry' => 'item'],
            'entryAttribute' => ['xml_entry_attribute' => 'name'],
            'inline'         => [
                'xml_inline'           => true,
                'xml_entry'            => 'inline',
                'xml_entry_attribute'  => 'index',
                'xml_key_as_attribute' => true,
                'xml_key_as_node'      => false,
            ],
        ], ['xml_root' => 'xml']);
    }

    public function testXmlValueFixture()
    {
        $classMetadata = new ClassMetadata(XmlValueFixture::class);

        $this->assertTrue($this->loadClassMetadata($classMetadata));
        $this->assertClassMetadata($classMetadata, [
            'foo' => ['xml_attribute' => true],
            'bar' => ['xml_value' => true],
        ], ['xml_root' => 'xml']);
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
     * @param mixed[]                $options
     */
    protected function assertClassMetadata(
        ClassMetadataInterface $classMetadata,
        array $properties,
        array $options = []
    ) {
        $this->assertSame(isset($options['xml_root']), $classMetadata->hasXmlRoot());
        $this->assertSame(isset($options['xml_root']) ? $options['xml_root'] : null, $classMetadata->getXmlRoot());

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
        $this->assertSame(isset($data['alias']), $propertyMetadata->hasAlias());
        $this->assertSame(isset($data['alias']) ? $data['alias'] : null, $propertyMetadata->getAlias());

        $this->assertSame(isset($data['type']), $propertyMetadata->hasType(), $propertyMetadata->getName());
        $this->assertSame(
            isset($data['type']) ? $data['type'] : null,
            $propertyMetadata->hasType() ? (string) $propertyMetadata->getType() : null
        );

        $this->assertSame(isset($data['readable']) ? $data['readable'] : true, $propertyMetadata->isReadable());
        $this->assertSame(isset($data['writable']) ? $data['writable'] : true, $propertyMetadata->isWritable());

        $this->assertSame(isset($data['accessor']), $propertyMetadata->hasAccessor());
        $this->assertSame(isset($data['accessor']) ? $data['accessor'] : null, $propertyMetadata->getAccessor());

        $this->assertSame(isset($data['mutator']), $propertyMetadata->hasMutator());
        $this->assertSame(isset($data['mutator']) ? $data['mutator'] : null, $propertyMetadata->getMutator());

        $this->assertSame(isset($data['since']), $propertyMetadata->hasSinceVersion());
        $this->assertSame(isset($data['since']) ? $data['since'] : null, $propertyMetadata->getSinceVersion());

        $this->assertSame(isset($data['until']), $propertyMetadata->hasUntilVersion());
        $this->assertSame(isset($data['until']) ? $data['until'] : null, $propertyMetadata->getUntilVersion());

        $this->assertSame(isset($data['max_depth']), $propertyMetadata->hasMaxDepth());
        $this->assertSame(isset($data['max_depth']) ? $data['max_depth'] : null, $propertyMetadata->getMaxDepth());

        $this->assertSame(isset($data['groups']), $propertyMetadata->hasGroups());
        $this->assertSame(isset($data['groups']) ? $data['groups'] : [], $propertyMetadata->getGroups());

        $this->assertSame(isset($data['xml_attribute']) && $data['xml_attribute'], $propertyMetadata->isXmlAttribute());
        $this->assertSame(isset($data['xml_inline']) && $data['xml_inline'], $propertyMetadata->isXmlInline());
        $this->assertSame(isset($data['xml_value']) && $data['xml_value'], $propertyMetadata->isXmlValue());
        $this->assertSame(isset($data['xml_entry']) ? $data['xml_entry'] : null, $propertyMetadata->getXmlEntry());

        $this->assertSame(
            isset($data['xml_entry_attribute']) ? $data['xml_entry_attribute'] : null,
            $propertyMetadata->getXmlEntryAttribute()
        );

        $this->assertSame(
            isset($data['xml_key_as_attribute']) ? $data['xml_key_as_attribute'] : null,
            $propertyMetadata->useXmlKeyAsAttribute()
        );

        $this->assertSame(
            isset($data['xml_key_as_node']) ? $data['xml_key_as_node'] : null,
            $propertyMetadata->useXmlKeyAsNode()
        );
    }
}
