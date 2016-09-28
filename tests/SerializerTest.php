<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer;

use Ivory\Serializer\Context\Context;
use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Exclusion\GroupsExclusionStrategy;
use Ivory\Serializer\Exclusion\MaxDepthExclusionStrategy;
use Ivory\Serializer\Exclusion\VersionExclusionStrategy;
use Ivory\Serializer\Format;
use Ivory\Serializer\Naming\CamelCaseNamingStrategy;
use Ivory\Serializer\Naming\KebabCaseNamingStrategy;
use Ivory\Serializer\Naming\SnakeCaseNamingStrategy;
use Ivory\Serializer\Naming\SpaceNamingStrategy;
use Ivory\Serializer\Naming\StudlyCapsNamingStrategy;
use Ivory\Serializer\Serializer;
use Ivory\Tests\Serializer\Fixture\AccessorFixture;
use Ivory\Tests\Serializer\Fixture\ArrayFixture;
use Ivory\Tests\Serializer\Fixture\AscFixture;
use Ivory\Tests\Serializer\Fixture\DateTimeFixture;
use Ivory\Tests\Serializer\Fixture\DescFixture;
use Ivory\Tests\Serializer\Fixture\ExcludeFixture;
use Ivory\Tests\Serializer\Fixture\ExposeFixture;
use Ivory\Tests\Serializer\Fixture\FixtureInterface;
use Ivory\Tests\Serializer\Fixture\GroupFixture;
use Ivory\Tests\Serializer\Fixture\IgnoreNullFixture;
use Ivory\Tests\Serializer\Fixture\MaxDepthFixture;
use Ivory\Tests\Serializer\Fixture\MutatorFixture;
use Ivory\Tests\Serializer\Fixture\NamingFixture;
use Ivory\Tests\Serializer\Fixture\OrderFixture;
use Ivory\Tests\Serializer\Fixture\ReadableClassFixture;
use Ivory\Tests\Serializer\Fixture\ReadableFixture;
use Ivory\Tests\Serializer\Fixture\ScalarFixture;
use Ivory\Tests\Serializer\Fixture\VersionFixture;
use Ivory\Tests\Serializer\Fixture\WritableClassFixture;
use Ivory\Tests\Serializer\Fixture\WritableFixture;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class SerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->serializer = new Serializer();
    }

    /**
     * @param string                $name
     * @param mixed                 $data
     * @param string                $format
     * @param ContextInterface|null $context
     *
     * @dataProvider serializeProvider
     */
    public function testSerialize($name, $data, $format, ContextInterface $context = null)
    {
        $this->assertSame($this->getDataSet($name, $format), $this->serializer->serialize($data, $format, $context));
    }

    /**
     * @param string                $name
     * @param mixed                 $data
     * @param string                $type
     * @param string                $format
     * @param ContextInterface|null $context
     *
     * @dataProvider deserializeProvider
     */
    public function testDeserialize($name, $data, $type, $format, ContextInterface $context = null)
    {
        $result = $this->serializer->deserialize($this->getDataSet($name, $format), $type, $format, $context);

        foreach ([&$data, &$result] as &$value) {
            if ($value instanceof FixtureInterface) {
                $value = $value->toArray();
            } elseif ($value instanceof \DateTimeInterface) {
                $value = $value->format(\DateTime::RFC3339);
            } elseif ($value instanceof \stdClass) {
                $value = (array) $value;
            }
        }

        $this->assertSame($data, $result);
    }

    /**
     * @return mixed[][]
     */
    public function serializeProvider()
    {
        $emptyArrayFixture = new ArrayFixture();
        $arrayFixture = clone $emptyArrayFixture;
        $arrayFixture->setScalars(['foo', 'bar' => 'baz']);
        $arrayFixture->setObjects([clone $emptyArrayFixture]);
        $arrayFixture->setTypes(['foo', 'bar']);
        $arrayFixture->setInceptions(['foo' => ['bar', 'baz']]);

        $date = '2017-01-01T00:00:00';
        $dateTime = new \DateTime($date, new \DateTimeZone('UTC'));
        $timeZonedDateTime = new \DateTime($date, new \DateTimeZone('Europe/Paris'));
        $dateTimeImmutable = new \DateTimeImmutable($date, new \DateTimeZone('UTC'));
        $timeZonedDateTimeImmutable = new \DateTimeImmutable($date, new \DateTimeZone('Europe/Paris'));

        $emptyDateTimeFixture = new DateTimeFixture();
        $dateTimeFixture = clone $emptyDateTimeFixture;
        $dateTimeFixture->dateTime = $dateTime;
        $dateTimeFixture->formattedDateTime = $dateTime;
        $dateTimeFixture->timeZonedDateTime = $timeZonedDateTime;
        $dateTimeFixture->immutableDateTime = $dateTimeImmutable;
        $dateTimeFixture->formattedImmutableDateTime = $dateTimeImmutable;
        $dateTimeFixture->timeZonedImmutableDateTime = $timeZonedDateTimeImmutable;

        $emptyIgnoreNullFixture = new IgnoreNullFixture();
        $ignoreNullFixture = clone $emptyIgnoreNullFixture;
        $ignoreNullFixture->foo = 'oof';
        $ignoreNullFixture->bar = [null, null];

        $namingFixture = new NamingFixture();
        $namingFixture->fooBar = 'foo';
        $namingFixture->baz_bat = 'baz';

        $excludeFixture = new ExcludeFixture();
        $excludeFixture->foo = 'oof';
        $excludeFixture->bar = 'rab';

        $exposeFixture = new ExposeFixture();
        $exposeFixture->foo = 'oof';
        $exposeFixture->bar = 'rab';

        $accessorFixture = new AccessorFixture();
        $accessorFixture->setName(' foo ');

        $mutatorFixture = new MutatorFixture();
        $mutatorFixture->setRawName(' foo ');

        $emptyGroupFixture = new GroupFixture();
        $groupFixture = clone $emptyGroupFixture;
        $groupFixture->setFoo('oof');
        $groupFixture->setBar('rab');
        $groupFixture->setBaz('zab');
        $groupFixture->setBat('tab');

        $emptyMaxDepthFixture = new MaxDepthFixture();
        $maxDepthFixture = clone $emptyMaxDepthFixture;
        $maxDepthFixture->setParent($maxDepthParentFixture = clone $emptyMaxDepthFixture);
        $maxDepthParentFixture->setParent(clone $emptyMaxDepthFixture);
        $maxDepthFixture->addChild($maxDepthChildFixture = clone $emptyMaxDepthFixture);
        $maxDepthFixture->orphanChildren[] = clone $emptyArrayFixture;
        $maxDepthChildFixture->addChild(clone $emptyMaxDepthFixture);

        $circularReference = new MaxDepthFixture();
        $circularReference->setParent($circularReference);

        $ascFixture = new AscFixture();
        $ascFixture->foo = 'oof';
        $ascFixture->bar = 'rab';

        $descFixture = new DescFixture();
        $descFixture->foo = 'oof';
        $descFixture->bar = 'rab';

        $orderFixture = new OrderFixture();
        $orderFixture->foo = 'oof';
        $orderFixture->bar = 'rab';

        $readableFixture = new ReadableFixture();
        $readableFixture->foo = 'oof';
        $readableFixture->bar = 'rab';

        $readableClassFixture = new ReadableClassFixture();
        $readableClassFixture->foo = 'oof';
        $readableClassFixture->bar = 'rab';

        $writableFixture = new WritableFixture();
        $writableFixture->foo = 'oof';
        $writableFixture->bar = 'rab';

        $writableClassFixture = new WritableClassFixture();
        $writableClassFixture->foo = 'oof';
        $writableClassFixture->bar = 'rab';

        $emptyScalarFixture = new ScalarFixture();
        $scalarFixture = clone $emptyScalarFixture;
        $scalarFixture->bool = true;
        $scalarFixture->float = 123.0;
        $scalarFixture->int = 123;
        $scalarFixture->string = 'foo';
        $scalarFixture->setType(clone $scalarFixture);

        $emptyScalarExtendedFixture = new ScalarFixture();
        $scalarExtendedFixture = clone $emptyScalarExtendedFixture;
        $scalarExtendedFixture->bool = true;
        $scalarExtendedFixture->float = 123.0;
        $scalarExtendedFixture->int = 123;
        $scalarExtendedFixture->string = 'foo';
        $scalarExtendedFixture->setType(clone $scalarExtendedFixture);

        $emptyStdClass = new \stdClass();
        $stdClass = clone $emptyStdClass;
        $stdClass->foo = 'bar';
        $stdClass->baz = ['bat'];

        $emptyVersionFixture = new VersionFixture();
        $versionFixture = clone $emptyVersionFixture;
        $versionFixture->foo = 'oof';
        $versionFixture->bar = 'rab';
        $versionFixture->baz = 'zab';
        $versionFixture->bat = 'tab';

        $cases = [
            ['array', []],
            ['boolean', true],
            ['integer', 123],
            ['float', 123.0],
            ['null', null],
            ['string', 'foo'],
            ['date_time', $dateTime],
            ['std_class', $stdClass],
            ['std_class_empty', $emptyStdClass],
            ['object_array', $arrayFixture],
            ['object_array_empty', $emptyArrayFixture],
            ['object_date_time', $dateTimeFixture],
            ['object_date_time_empty', $emptyDateTimeFixture],
            ['object_ignore_null', $ignoreNullFixture, (new Context())->setIgnoreNull(true)],
            ['object_ignore_null_empty', $emptyIgnoreNullFixture, (new Context())->setIgnoreNull(true)],
            ['object_naming', $namingFixture],
            ['object_naming_camel_case', $namingFixture, (new Context())->setNamingStrategy(new CamelCaseNamingStrategy())],
            ['object_naming_kebab_case', $namingFixture, (new Context())->setNamingStrategy(new KebabCaseNamingStrategy())],
            ['object_naming_snake_case', $namingFixture, (new Context())->setNamingStrategy(new SnakeCaseNamingStrategy())],
            ['object_naming_space', $namingFixture, (new Context())->setNamingStrategy(new SpaceNamingStrategy())],
            ['object_naming_studly_caps', $namingFixture, (new Context())->setNamingStrategy(new StudlyCapsNamingStrategy())],
            ['object_exclude', $excludeFixture],
            ['object_expose', $exposeFixture],
            ['object_accessor', $accessorFixture],
            ['object_mutator', $mutatorFixture],
            ['object_groups', $groupFixture],
            ['object_groups_empty', $emptyGroupFixture],
            ['object_groups_group1', $groupFixture, (new Context())->setExclusionStrategy(new GroupsExclusionStrategy(['group1']))],
            ['object_groups_group2', $groupFixture, (new Context())->setExclusionStrategy(new GroupsExclusionStrategy(['group2']))],
            ['object_groups_group1_group2', $groupFixture, (new Context())->setExclusionStrategy(new GroupsExclusionStrategy(['group1', 'group2']))],
            ['object_circular_reference', $circularReference, (new Context())->setExclusionStrategy(new MaxDepthExclusionStrategy())],
            ['object_max_depth', $maxDepthFixture, (new Context())->setExclusionStrategy(new MaxDepthExclusionStrategy())],
            ['object_max_depth_empty', $emptyMaxDepthFixture],
            ['object_order', $orderFixture],
            ['object_order_asc', $ascFixture],
            ['object_order_desc', $descFixture],
            ['object_readable', $readableFixture],
            ['object_readable_class', $readableClassFixture],
            ['object_writable', $writableFixture],
            ['object_writable_class', $writableClassFixture],
            ['object_scalar', $scalarFixture],
            ['object_scalar_empty', $emptyScalarFixture],
            ['object_scalar', $scalarExtendedFixture],
            ['object_scalar_empty', $emptyScalarExtendedFixture],
            ['object_version_empty', $emptyVersionFixture],
            ['object_version_0_9', $versionFixture, (new Context())->setExclusionStrategy(new VersionExclusionStrategy('0.9'))],
            ['object_version_1_0', $versionFixture, (new Context())->setExclusionStrategy(new VersionExclusionStrategy('1.0'))],
            ['object_version_2_0', $versionFixture, (new Context())->setExclusionStrategy(new VersionExclusionStrategy('2.0'))],
            ['object_version_2_1', $versionFixture, (new Context())->setExclusionStrategy(new VersionExclusionStrategy('2.1'))],
        ];

        $providers = [];

        foreach ([Format::CSV, Format::JSON, Format::XML, Format::YAML] as $format) {
            foreach ($cases as $case) {
                if (isset($case[2])) {
                    $case[3] = $case[2];
                }

                $case[2] = $format;
                $providers[] = $case;
            }
        }

        return $providers;
    }

    /**
     * @return mixed[][]
     */
    public function deserializeProvider()
    {
        $providers = [];
        $serializeProviders = self::serializeProvider();

        foreach ($serializeProviders as $provider) {
            if (isset($provider[3])) {
                continue;
            }

            $providers[] = [
                $provider[0],
                $data = $provider[1],
                is_object($data) ? get_class($data) : strtolower(gettype($data)),
                $provider[2],
            ];
        }

        return $providers;
    }

    /**
     * @param string $name
     * @param string $format
     *
     * @return string
     */
    private function getDataSet($name, $format)
    {
        $extension = $format;

        if ($extension === Format::YAML) {
            $extension = 'yml';
        }

        return file_get_contents(__DIR__.'/Fixture/data/'.strtolower($format).'/'.$name.'.'.strtolower($extension));
    }
}
