# Context

The context allows you to inject runtime configuration into the serializer. It allows you to configure exclusion groups 
as well as the application version.

## Build

Let's create a context:

``` php
use Ivory\Serializer\Context\Context;

$context = new Context();
```

## Groups

The groups exclusion strategy allows you to exclude class properties according to groups:

``` php
$context->setGroups(['group1', 'group2']);
```

## Version

The version exclusion strategy allows you to exclude class properties according to your application version. Basically, 
you can expose or exclude fields based on a version:

``` php
$context->setVersion('1.0.1');
```

## Use it!

Then, you can use this context when you (de)-serialize:

``` php
use Ivory\Serializer\Format;

$json = $serializer->serialize(new \stdClass(), Format::JSON, $context);
// or
$object = $serializer->deserialize($json, \stdClass::class, Format::JSON, $context);
```
