# Type

When you deserialize your data or when you configure your metadata mapping, you can specify a type. This type is not 
mandatory except for deserializing but it is highly recommended to configure it in order to make the library faster.

A type is a generic structure which follow the pattern `type` or `type<option1=value, option2=value, option3=value>`.

## Built-in

The library is shipped with some built-in types:

| Type                                         | Description                            |
| -------------------------------------------- | -------------------------------------- |
| `bool`                                       | Boolean type                           |
| `boolean`                                    | Alias of `bool`                        |
| `double`                                     | Alias of `float`                       |
| `float`                                      | Float type                             |
| `numeric`                                    | Alias of `float`                       |
| `int`                                        | Integer type                           |
| `integer`                                    | Alias of `int`                         |
| `string`                                     | String type                            |
| `array`                                      | Array type                             |
| `array<value=type>`                          | Array type with typed value            |
| `array<key=type, value=type>`                | Array type with typed key and value    |
| `DateTime`                                   | `DateTime` type                        |
| `DateTime<format='Y-m-d\TH:i:sP'>`           | `DateTime` type with format            |
| `DateTime<timezone='Europe/Paris'>`          | `DateTime` type with timezone          |
| `DateTimeImmutable`                          | `DateTimeImmutable` type               |
| `DateTimeImmutable<format='Y-m-d\TH:i:sP'>`  | `DateTimeImmutable` type with format   |
| `DateTimeImmutable<timezone='Europe/Paris'>` | `DateTimeImmutable` type with timezone |
| `Fully\Qualified\Class\Name`                 | Object type                            |

### DateTime & DateTimeImmutable

The `DateTime` and `DateTimeImmutable` types use the `DateTime::RFC3339` as default format and rely on
`date_default_timezone_get` to determine the default timezone.

### Exception

By default, the serializer does not expose exception data but generate a generic structure (code => 500, message => 
Internal Server Error). If your want to get more informations about the exception when you are in development mode 
for example, you can use the following:

``` php
use Ivory\Serializer\Registry\TypeRegistry;
use Ivory\Serializer\Serializer;
use Ivory\Serializer\Type\ExceptionType;
use Ivory\Serializer\Type\Type;

$typeRegistry = TypeRegistry::create([
    Type::EXCEPTION => new ExceptionType(true), 
]);

$serializer = new Serializer(new Navigator($typeRegistry));
```

## Custom

If you want to create your own type for a specific class, you need to implement the
`Ivory\Serializer\Type\TypeInterface`:

``` php
namespace Acme\Serializer\Type;

use Ivory\Serializer\Direction;
use Ivory\Serializer\Type\TypeInterface;

class AcmeObjectType implements TypeInterface
{
    public function convert($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        return $context->getDirection() === Direction::SERIALIZATION
            ? $this->serialize($data, $type, $context)
            : $this->deserialize($data, $type, $context);
    }

    private function serialize($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        // Visit your data
    }

    private function deserialize($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        // Visit your data
    }
}
```

Then, just need to register your type in the serializer. When you will serialize or deserialize an `AcmeObject` or any 
object which extends it, then, your custom type will be triggered:

``` php
use Acme\Model\AcmeObject;
use Acme\Serializer\Type\AcmeObjectType;
use Ivory\Serializer\Registry\TypeRegistry;
use Ivory\Serializer\Serializer;

$typeRegistry = TypeRegistry::create([
    AcmeObject::class => new AcmeObjectType(), 
]);

$serializer = new Serializer(new Navigator($typeRegistry));
```

You can also register a type just for a specific direction (eg serialization or deserialization):

``` php
use Acme\Model\AcmeObject;
use Acme\Serializer\Type\AcmeObjectType;
use Ivory\Serializer\Direction;
use Ivory\Serializer\Registry\TypeRegistry;
use Ivory\Serializer\Serializer;

$typeRegistry = TypeRegistry::create([
    Direction::SERIALIZATION => [
        AcmeObject::class => new AcmeObjectType(),
    ],
]);

$serializer = new Serializer(new Navigator($typeRegistry));
```
