# Visitor

When you (de)-serialize your data, the serializer will choose a visitor according to your format (csv, json, ...) and
your direction (serialization or deserialization). Each format/direction have a dedicated visitor in order to
handle this specific use case.

## Built-in

The library is shipped with some built-in visitors:

| Name | Serialization            | Deserialization            |
| ---- | ------------------------ | -------------------------- |
| CSV  | CsvSerializationVisitor  | CsvDeserializationVisitor  |
| JSON | JsonSerializationVisitor | JsonDeserializationVisitor |
| XML  | XmlSerializationVisitor  | XmlDeserializationVisitor  |
| YAML | YamlSerializationVisitor | YamlDeserializationVisitor |

## Custom

If you want to create your own visitor for a new format for example, you can implement the
`Ivory\Serializer\Visitor\VisitorInterface` or extend the `Ivory\Serializer\Visitor\AbstractSerializationVisitor`
for serializing or the `Ivory\Serializer\Visitor\AbstractDeserializationVisitor` for deserializing.

**Implementing a visitor is a tedious work and require to master the internal of the library...**

Once you have created your visitor, you need to register it on the Serializer in order to use it:

``` php
use Acme\Serializer\Visitor\CustomDeserializationVisitor;
use Acme\Serializer\Visitor\CustomSerializationVisitor;
use Ivory\Serializer\Direction;
use Ivory\Serializer\Serializer;
use Ivory\Serializer\Visitor\VisitorRegistry;

$format = 'custom';

$visitorRegistry = VisitorRegistry::create([
    Direction::SERIALIZATION => [
        $format => new CustomSerializationVisitor(),
    ],
    Direction::DESERIALIZATION => [
        $format => new CustomDeserializationVisitor(),
    ],
]);

$serializer = new Serializer(null, $visitorRegistry);

$stdClass = new \stdClass();
$stdClass->foo = true;
$stdClass->bar = ['foo', [123, 432.1]];

echo $serializer->serialize($stdClass, $format);
```
