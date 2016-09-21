# README

[![Build Status](https://travis-ci.org/egeloen/ivory-serializer.svg?branch=master)](http://travis-ci.org/egeloen/ivory-serializer)
[![Code Coverage](https://scrutinizer-ci.com/g/egeloen/ivory-serializer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/egeloen/ivory-serializer/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/egeloen/ivory-serializer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/egeloen/ivory-serializer/?branch=master)
[![Dependency Status](http://www.versioneye.com/php/egeloen:serializer/badge.svg)](http://www.versioneye.com/php/egeloen:serializer)

[![Latest Stable Version](https://poser.pugx.org/egeloen/serializer/v/stable.svg)](https://packagist.org/packages/egeloen/serializer)
[![Latest Unstable Version](https://poser.pugx.org/egeloen/serializer/v/unstable.svg)](https://packagist.org/packages/egeloen/serializer)
[![Total Downloads](https://poser.pugx.org/egeloen/serializer/downloads.svg)](https://packagist.org/packages/egeloen/serializer)
[![License](https://poser.pugx.org/egeloen/serializer/license.svg)](https://packagist.org/packages/egeloen/serializer)

The Ivory Serializer allows you to (de)-serialize any scalar data as well as complex object graph. It supports the 
JSON, XML & YAML formats.

``` php
use Acme\Model\ComplexObject;
use Ivory\Serializer\Format;
use Ivory\Serializer\Serializer;

$serializer = new Serializer();
$data = new ComplexObject();

$json = $serializer->serialize($data, Format::JSON);
$object = $serializer->deserialize($json, ComplexObject::class, Format::JSON);
```

## Documentation

FIXME - Write me...

## Testing

The library is fully unit tested by [PHPUnit](http://www.phpunit.de/) with a code coverage close to **100%**. To
execute the test suite, check the travis [configuration](/.travis.yml).

## Contribute

We love contributors! Ivory is an open source project. If you'd like to contribute, feel free to propose a PR!.

## License

The Ivory Serializer is under the MIT license. For the full copyright and license information, please read the
[LICENSE](/LICENSE) file that was distributed with this source code.
