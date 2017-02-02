# Event

The Serializer is shipped with a [Symfony Event Dispatcher](http://symfony.com/doc/current/components/event_dispatcher.html) 
integration. It allows you to hook into the class metadata loading and pre/post (de)-serialization. The following 
snippet exposes you how to set up your serializer in order to make it aware of your event dispatcher.

``` php
use Ivory\Serializer\Maping\Factory\ClassMetadataFactory;
use Ivory\Serializer\Maping\Factory\EventClassMetadataFactory;
use Ivory\Serializer\Navigator\EventNavigator;
use Ivory\Serializer\Navigator\Navigator;
use Ivory\Serializer\Registry\TypeRegistry;
use Ivory\Serializer\Serializer;
use Ivory\Serializer\Type\ObjectType;
use Ivory\Serializer\Type\Type;
use Symfony\Component\EventDispatcher\EventDispatcher;

$dispatcher = new EventDispatcher();
// Register listeners/subscribers

$classMetadataFactory = new EventClassMetadataFactory(
    ClassMetadataFactory::create(),
    $dispatcher
);

$typeRegistry = TypeRegistry::create([
    Type::OBJECT => new ObjectType($classMetadataFactory),
]);

$serializer = new Serializer(new EventNavigator(
    new Naviagtor($typeRegistry), 
    $dispatcher
));
```

## Events

The Serializer dispatches events according to what you're currently doing. All events can be found in the 
`Ivory\Serializer\Event\SerializerEvents` as constants.

### Pre Serialize

The pre serialize event is dispatched before a type is navigated for serialization and wrap the data, its type and the 
context. This event offers the ability to update the data as well as its type just before a serialization. 
 
``` php
use Ivory\Serializer\Event\PreSerializeEvent;
use Ivory\Serializer\Event\SerializerEvents;

$dispatcther->addListsner(
    SerializerEvents::PRE_SERIALIZE,
    function (PreSerializeEvent $event) {
        // Hook before the serialization
    }
);
```

### Post Serialize

The post serialize event is dispatched after a type is navigated for serialization and wrap the data, its type and the 
context. 
 
``` php
use Ivory\Serializer\Event\PostSerializeEvent;
use Ivory\Serializer\Event\SerializerEvents;

$dispatcther->addListsner(
    SerializerEvents::POST_SERIALIZE,
    function (PostSerializeEvent $event) {
        // Hook after the serialization
    }
);
```

### Pre Deserialize

The pre deserialize event is dispatched before a type is navigated for deserialization and wrap the data, its type and 
the context. This event offers the ability to update the data as well as its type just before a deserialization. 
 
``` php
use Ivory\Serializer\Event\PreDeserializeEvent;
use Ivory\Serializer\Event\SerializerEvents;

$dispatcther->addListsner(
    SerializerEvents::PRE_DESERIALIZE,
    function (PreDeserializeEvent $event) {
        // Hook before the deserialization
    }
);
```

### Post Deserialize

The post deserialize event is dispatched after a type is navigated for deserialization and wrap the data, its type and 
the context. 
 
``` php
use Ivory\Serializer\Event\PostDeserializeEvent;
use Ivory\Serializer\Event\SerializerEvents;

$dispatcther->addListsner(
    SerializerEvents::POST_DESERIALIZE,
    function (PostDeserializeEvent $event) {
        // Hook after the deserialization
    }
);
```

### Class Metadata Load

The class metadata load event is dispatched after a class metadata is loaded. This event wraps the class metadata and 
allows you to update it according to your needs. 
 
``` php
use Ivory\Serializer\Event\ClassMetadataLoadEvent;
use Ivory\Serializer\Event\SerializerEvents;

$dispatcther->addListsner(
    SerializerEvents::CLASS_METADATA_LOAD,
    function (ClassMetadataLoadEvent $event) {
        // Update the class metadata
    }
);
```

### Class Metadata Not Found

The class metadata not found event is dispatched when there is no loader able to load a class metadata. This event 
wraps the class name requested and allows you to inject your own class metadata. 
 
``` php
use Ivory\Serializer\Event\ClassMetadataNotFoundEvent;
use Ivory\Serializer\Event\SerializerEvents;

$dispatcther->addListsner(
    SerializerEvents::CLASS_METADATA_NOT_FOUND,
    function (ClassMetadataNotFoundEvent $event) {
        // Inject a class metadata
    }
);
```
