# Mapping

The metadata mapping allows you to expose your object structure to the serializer.

## Definition

A mapping is a set of definitions which allow you to configure how your object should be (de)-serialized.

### Property

 - [Alias](/doc/definition/alias.md): Rename a property.
 - [Type](/doc/definition/type.md): Configure a property type.
 - [Order](/doc/definition/order.md): Configure properties order.

### Visibility

 - [Readable](/doc/definition/readable.md): Configure if a property is serializable.
 - [Writable](/doc/definition/writable.md): Configure if a property is de-serializable.
 
### Access
 
 - [Accessor](/doc/definition/accessor.md): Configure how to get a property when serializing.
 - [Mutator](/doc/definition/mutator.md): Configure how to set a property when de-serializing.
 
### Exclusion

 - [Exclusion Policy](/doc/definition/exclusion_policy.md): Configure global property exclusions.
 - [Groups](/doc/definition/groups.md): Configure exclusion based on property groups.
 - [MaxDepth](/doc/definition/max_depth.md): Configure exclusion based on property max depth.
 - [Version](/doc/definition/version.md): Configure from which version a property is available.
 
### Xml

 - [XmlRoot](/doc/definition/xml_root.md): Configure the XML root node of a class.
 - [XmlAttribute](/doc/definition/xml_attribute.md): Configure if a property is an XML attribute.
 - [XmlValue](/doc/definition/xml_value.md): Configure if a property is an XML value.
 - [XmlCollection](/doc/definition/xml_collection.md): Configure how a collection is (de)-serialized in XML.

## Factory

In order to create your metadatas, the library uses a factory relying on a loader. Let's create a factory:

``` php
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactory;

// Create a factory with reflection/annotation loaders
$classMetadataFactory = ClassMetadataFactory::create();

// Create a factory with an array of loaders
$classMetadataFactory = ClassMetadataFactory::create([
    $loader1,
    $loader2,
    // ...
]);

// Create a factory with an explicit loader
$classMetadataFactory = new ClassMetadataFactory($loader);
```

Once you have created your factory, you need to register it on the serializer:

``` php
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactory;
use Ivory\Serializer\Navigator\Navigator;
use Ivory\Serializer\Registry\TypeRegistry;
use Ivory\Serializer\Serializer;
use Ivory\Serializer\Type\ObjectType;
use Ivory\Serializer\Type\Type;

// Create a factory
$classMetadataFactory = ClassMetadataFactory::create();

// Register the factory
$typeRegistry = TypeRegistry::create([
    Type::OBJECT => new ObjectType($cacheClassMetadataFactory),
]);

// Create the serializer
$serializer = new Serializer(new Navigator($typeRegistry));
```

In production, it is highly recommended to use the cache factory in order to avoid parsing class and property metadata
on each requests:

``` php
use Ivory\Serializer\Mapping\Factory\CacheClassMetadataFactory;
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactory;

// Create a factory
$classMetadataFactory = new ClassMetadataFactory($loader);

// Create a cached factory using a PSR-6 pool 
$cacheClassMetadataFactory = new CacheClassMetadataFactory($classMetadataFactory, $psr6CachePool);
```

In production, it is also highly recommended to warm up your cache during your deployment process in order to avoid
parsing class and property metadatas the first time they are needed:

``` php
use Ivory\Serializer\Mapping\Loader\MappedClassMetadataLoaderInterface;

if (!$loader instanceof MappedClassMetadataLoaderInterface) {
    // Unable to warm up the cache
    return;
}

// Warm up the cache
foreach ($loader->getMappedClasses() as $class) {
    $cacheClassMetadataFactory->getClassMetadata($class);
}

$psr6CachePool->commit();
```

## Loader

### Reflection

The reflection loader is used by default if you don't provide loaders manually. It is able to find all your
properties/methods for (de)-serialization based on the reflection.

``` php
use Ivory\Serializer\Mapping\Loader\ReflectionClassMetadataLoader;

$loader = new ReflectionClassMetadataLoader();
```

If you create it manually, be aware that the reflection loader requires a property info extractor 
([`symfony/property-info`](http://symfony.com/doc/current/components/property_info.html)) in order to efficiently 
detect types during deserialization.

``` php
use Ivory\Serializer\Mapping\Loader\ReflectionClassMetadataLoader;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
            
$extractor = new PropertyInfoExtractor(/* ... */);
$loader = new ReflectionClassMetadataLoader($propertyInfoExtractor);
```

### Annotation

The annotation loader is used by default if the `doctrine/annotation` library is installed and you don't provide 
loaders manually. This loader will use the annotations put on your class:

``` php
use Doctrine\Common\Annotations\AnnotationReader;
use Ivory\Serializer\Mapping\Loader\AnnotationClassMetadataLoader;

$reader = new AnnotationReader();
$loader = new AnnotationClassMetadataLoader($reader);
```

### XML

The XML loader allows you to use a XML mapping file:

``` php
use Ivory\Serializer\Mapping\Loader\XmlClassMetadataLoader;

$loader = new XmlClassMetadataLoader('/path/to/file.xml');
```

### YAML

The YAML loader allows you to use a YAML mapping file:

``` php
use Ivory\Serializer\Mapping\Loader\YamlClassMetadataLoader;

$loader = new YamlClassMetadataLoader('/path/to/file.yml');
```

### JSON

The JSON loader allows you to use a JSON mapping file:

``` php
use Ivory\Serializer\Mapping\Loader\JsonClassMetadataLoader;

$loader = new JsonClassMetadataLoader('/path/to/file.json');
```

## File

The file loader allows you tu use JSON, XML and YAML loaders without taking care of the format:

``` php
use Ivory\Serializer\Mapping\Loader\FileClassMetadataLoader;

$loader = new FileClassMetadataLoader($file);
```

### Directory

The directory loader allows you to use JSON, XML and YAML loaders by discovering mapping files recursively in one or 
multiple directories:

``` php
use Ivory\Serializer\Mapping\Loader\DirectoryClassMetadataLoader;

$loader = new DirectoryClassMetadataLoader('/path/to/mapping');
// or
$loader = new DirectoryClassMetadataLoader([
    '/path/to/mapping1',
    '/path/to/mapping2',
]);
```

### Chain

The chain loader allows you to load a metadata by delegating it to a chain of loaders. When loading a metadata, the 
chain loader will invoke all loaders in the chain regardless if the previous has loaded the metadata or not. This allow 
us to support multiple metadata formats in the same application.

``` php
use Ivory\Serializer\Mapping\Loader\AnnotationClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\ChainClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\DirectoryClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\JsonClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\ReflectionClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\XmlClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\YamlClassMetadataLoader;

$loaders = [
    new ReflectionClassMetadataLoader(),
    new AnnotationClassMetadataLoader(),
    new XmlClassMetadataLoader('/path/to/file.xml'),
    new YamlClassMetadataLoader('/path/to/file.yml'),
    new JsonClassMetadataLoader('/path/to/file.json'),
    new DirectoryClassMetadataLoader('/path/to/mapping'),
];

$loader = new ChainClassMetadataLoader($loaders);
```
