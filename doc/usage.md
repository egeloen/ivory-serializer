# Usage

The central point of the library is the `Ivory\Serializer\Serializer` which will allow you to serialize and deserialize 
your data:

``` php
use Ivory\Serializer\Serializer;

$serializer = new Serializer();
```

## Serialization

For serializing data, call `serialize` with your data as first argument and your format as second argument:

``` php
use Acme\Model\AcmeObject;
use Ivory\Serializer\Format;

$json = $serializer->serialize(new AcmeObject(), Format::JSON);
```

The `serialize` method also accepts a context as third argument which is useful if you want to exclude properties 
according to groups or API version. If you want to learn more about it, you can read this 
[documentation](/doc/context.md).

``` php
use Acme\Model\AcmeObject;
use Ivory\Serializer\Context\Context;
use Ivory\Serializer\Format;

$context = new Context();
// Configure the context...

$json = $serializer->serialize(
    new AcmeObject(), 
    Format::JSON, 
    $context
);
```

## Deserialization

For deserializing data, call `deserialize` with your data as first argument, your type as second argument and your 
format as third argument:

``` php
use Acme\Model\AcmeObject;
use Ivory\Serializer\Format;

$object = $serializer->deserialize('{"foo":"bar"}', AcmeObject::class, Format::JSON);
```

The `deserialize` method also accepts a context as fourth argument which is useful if you want to exclude properties 
according to groups or API version. If you want to learn more about it, you can read this 
[documentation](/doc/context.md).

``` php
use Acme\Model\AcmeObject;
use Ivory\Serializer\Context\Context;
use Ivory\Serializer\Format;

$context = new Context();
// Configure the context...

$object = $serializer->deserialize(
    '{"foo":"bar"}', 
    AcmeObject::class, 
    Format::JSON, 
    $context
);
```
