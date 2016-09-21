# Exclusion

If you want to exclude fields based on groups or API version or restrict depth of the (de)-serialization, you can use 
the exclusion strategy.

## Context

The context allows you to configure some exclusion strategies, so let's create a context:

``` php
use Ivory\Serializer\Context\Context;

$context = new Context();
```

Then, you can use this context when you (de)-serialize:

``` php
use Ivory\Serializer\Format;

$json = $serializer->serialize(new \stdClass(), Format::JSON, $context);
// or
$object = $serializer->deserialize($json, \stdClass::class, Format::JSON, $context);
```

## Groups

The groups exclusion strategy allows you to exclude object property according to groups. To configure the groups, you 
configure them on your context:

``` php
$context->setGroups(['group1', 'group2']);
```

Then, the serializer will use your metadata [mapping](/doc/mapping.md) in order to exclude your object properties.

## Max Depth

The max depth exclusion strategy does not require configuration. It simply relies on your metadata 
[mapping](/doc/mapping.md) and the traversed data in order to detect max depth exclusion.

## API Version

The version exclusion strategy allows you to exclude object property according to a version. To configure the version, 
you can configure it on your context:

``` php
$context->setVersion('1.0.1');
```

Then, the serializer will use your metadata [mapping](/doc/mapping.md) in order to exclude your object properties.
