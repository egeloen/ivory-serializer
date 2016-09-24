# README

[![Build Status](https://travis-ci.org/egeloen/ivory-serializer.svg?branch=master)](http://travis-ci.org/egeloen/ivory-serializer)
[![Code Coverage](https://scrutinizer-ci.com/g/egeloen/ivory-serializer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/egeloen/ivory-serializer/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/egeloen/ivory-serializer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/egeloen/ivory-serializer/?branch=master)
[![Dependency Status](http://www.versioneye.com/php/egeloen:serializer/badge.svg)](http://www.versioneye.com/php/egeloen:serializer)

[![Latest Stable Version](https://poser.pugx.org/egeloen/serializer/v/stable.svg)](https://packagist.org/packages/egeloen/serializer)
[![Latest Unstable Version](https://poser.pugx.org/egeloen/serializer/v/unstable.svg)](https://packagist.org/packages/egeloen/serializer)
[![Total Downloads](https://poser.pugx.org/egeloen/serializer/downloads.svg)](https://packagist.org/packages/egeloen/serializer)
[![License](https://poser.pugx.org/egeloen/serializer/license.svg)](https://packagist.org/packages/egeloen/serializer)

The Ivory Serializer is a PHP 5.6+ library allowing you to (de)-serialize complex data using the visitor pattern on 
each node of the graph recursively. Natively, it supports JSON, XML and YAML. It also supports features such as 
exclusion strategies (groups, max depth, version), naming strategies (camel case, snake case, studly caps), 
automatic/explicit mapping (reflection, annotation, XML, YAML, JSON) and many others...

``` php
use Ivory\Serializer\Format;
use Ivory\Serializer\Serializer;

$stdClass = new \stdClass();
$stdClass->foo = true;
$stdClass->bar = ['foo', [123, 432.1]];

$serializer = new Serializer();

echo $serializer->serialize($stdClass, Format::JSON);
// {"foo": true,"bar": ["foo", [123, 432.1]]}

$deserialize = $serializer->deserialize($json, \stdClass::class, Format::JSON);
// $deserialize == $stdClass
```

## Documentation

  - [Installation](/doc/installation.md)
  - [Usage](/doc/usage.md)
  - [Type](/doc/type.md)
  - [Context](/doc/context.md)
    - [Exclusion strategies](/doc/context.md#exclusion-strategies)
    - [Naming strategies](/doc/context.md#naming-strategies)
  - [Mapping](/doc/mapping.md)
    - [Reference](/doc/mapping/reference.md)
    - [Annotation](/doc/mapping/annotation.md)
    - [XML](/doc/mapping/xml.md)
    - [YAML](/doc/mapping/yaml.md)
    - [JSON](/doc/mapping/json.md)

## Testing

The library is fully unit tested by [PHPUnit](http://www.phpunit.de/) with a code coverage close to **100%**. To
execute the test suite, check the travis [configuration](/.travis.yml).

## Contribute

We love contributors! Ivory is an open source project. If you'd like to contribute, feel free to propose a PR!.

## License

The Ivory Serializer is under the MIT license. For the full copyright and license information, please read the
[LICENSE](/LICENSE) file that was distributed with this source code.
