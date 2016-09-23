# Mapping

The metadata mapping allows you to expose your object structure to the serializer.

## Factory

In order to create your metadatas, the library uses a factory relying on a loader. Let's create a factory: 

``` php
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactory;

$classMetadataFactory = new ClassMetadataFactory();
// or
$classMetadataFactory = new ClassMetadataFactory($loader);
```

In production, it is highly recommended to use the cache factory: 

``` php
use Ivory\Serializer\Mapping\Factory\CacheClassMetadataFactory;
use Ivory\Serializer\Mapping\Factory\ClassMetadataFactory;
use Ivory\Serializer\Registry\TypeRegistry;
use Ivory\Serializer\Type\ObjectType;
use Ivory\Serializer\Type\Type;

$classMetadataFactory = new ClassMetadataFactory();
$cacheClassMetadataFactory = new CacheClassMetadataFactory($classMetadataFactory, $psr6CachePool);

$typeRegistry = TypeRegistry::create([
    Type::OBJECT => new ObjectType($cacheClassMetadataFactory),
]);

$serializer = new Serializer(new Navigator($typeRegistry));
```

## Loader

### Reflection

The reflection loader is the default one if doctrine annotation is not installed. It is able to find all your 
properties for serialization but will not be sufficient for deserialization since it is not able to parse complex 
types.

``` php
use Ivory\Serializer\Mapping\Loader\ReflectionClassMetadataLoader;

$loader = new ReflectionClassMetadataLoader();
```

### Annotation

The annotation loader works the same way as the reflection one except that it also parses annotations.

``` php
use Ivory\Serializer\Mapping\Loader\AnnotationClassMetadataLoader;

$loader = new AnnotationClassMetadataLoader();
```

An example of all annotations is available [here](/doc/mapping/annotation.md).

### XML

The XML loader allows you to use a XML mapping file:

``` php
use Ivory\Serializer\Mapping\Loader\XmlClassMetadataLoader;

$loader = new XmlClassMetadataLoader('/path/to/file.xml');
```

An example of XML configuration is available [here](/doc/mapping/xml.md).

### YAML

The YAML loader allows you to use a YAML mapping file:

``` php
use Ivory\Serializer\Mapping\Loader\YamlClassMetadataLoader;

$loader = new YamlClassMetadataLoader('/path/to/file.yml');
```

An example of YAML configuration is available [here](/doc/mapping/yaml.md).

### JSON

The JSON loader allows you to use a JSON mapping file:

``` php
use Ivory\Serializer\Mapping\Loader\JsonClassMetadataLoader;

$loader = new JsonClassMetadataLoader('/path/to/file.json');
```

An example of JSON configuration is available [here](/doc/mapping/json.md).

### Chain

The chain loader allows you to load a metadata by delegating it to a chain of loaders. When loading a metadata, the 
chain loader will invoke all loaders in the chain regardless if the previous has loaded the metadata or not. This allow 
us to support multiple metadata formats in the same application.

``` php
use Ivory\Serializer\Mapping\Loader\AnnotationClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\ChainClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\JsonClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\XmlClassMetadataLoader;
use Ivory\Serializer\Mapping\Loader\YamlClassMetadataLoader;

$loaders = [
    new AnnotationClassMetadataLoader(),
    new XmlClassMetadataLoader('/path/to/file.xml'),
    new YamlClassMetadataLoader('/path/to/file.yml'),
    new JsonClassMetadataLoader('/path/to/file.json'),
];

$loader = new ChainClassMetadataLoader($loaders);
```
