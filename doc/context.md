# Context

The context allows you to inject runtime configuration into the serializer. It allows you to configure an exclusion 
strategy as well as a naming strategy.

## Build

Let's create a context and use it:

``` php
use Ivory\Serializer\Context\Context;
use Ivory\Serializer\Format;

$context = new Context();
// Configure the context

$json = $serializer->serialize(new \stdClass(), Format::JSON, $context);
// or
$object = $serializer->deserialize($json, \stdClass::class, Format::JSON, $context);
```

## Exclusion Strategies

### Max Depth

In order to control depth navigation of your graph, you have to use the max depth exclusion strategy:

``` php
use Ivory\Serializer\Exclusion\MaxDepthExclusionStrategy;

$context->setExclusionStrategy(new MaxDepthExclusionStrategy());
```

**Since this exclusion impacts performance, you need to explicitly enable it otherwise your mapping configuration 
is ignored.**

## Circular Reference

The circular reference strategy allows you to stop the graph traversal when a circular reference is detected:  

``` php
use Ivory\Serializer\Exclusion\CircularReferenceExclusionStrategy;

$context->setExclusionStrategy(new CircularReferenceExclusionStrategy());
```

**Since this exclusion impacts performance, you need to explicitly enable it otherwise your (de)-serialization will 
enter in an infinite recursion.**

### Groups

The groups exclusion strategy allows you to exclude class properties according to groups:

``` php
use Ivory\Serializer\Exclusion\GroupsExclusionStrategy;

$context->setExclusionStrategy(new GroupsExclusionStrategy(['group1', 'group2']));
```

### Version

The version exclusion strategy allows you to exclude class properties according to your application version:

``` php
use Ivory\Serializer\Exclusion\VersionExclusionStrategy;

$context->setExclusionStrategy(new VersionExclusionStrategy('1.0.1'));
```

### Chain

The chain exclusion strategy allows you to use multiple exclusion strategies together:

``` php
use Ivory\Serializer\Exclusion\ChainExclusionStrategy;
use Ivory\Serializer\Exclusion\GroupsExclusionStrategy;
use Ivory\Serializer\Exclusion\MaxDepthExclusionStrategy;
use Ivory\Serializer\Exclusion\VersionExclusionStrategy;

$context->setExclusionStrategy(new ChainExclusionStrategy([
    new MaxDepthExclusionStrategy(),
    new GroupsExclusionStrategy(['group1', 'group2']),
    new VersionExclusionStrategy('1.0.1'),
]));
```

## Naming Strategies

The naming strategy allows you to convert a property name into a different representation such as snake case, camel 
case, ... By default the serializer uses the identical strategy meaning no transformations are performed but it's up 
to you to configure a different naming strategy.

### Identical

The identical naming strategy (default) uses the property name without altering it:

``` php
$context->setNamingStrategy(new IdenticalNamingStrategy());
```

### Camel Case

FIXME - Implement me...

### Snake Case

FIXME - Implement me...

### Studly Caps

FIXME - Implement me...

### Kebab Case

FIXME - Implement me...
