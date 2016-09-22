# Type

When you deserialize your data or when you configure your metadata mapping, you can specify a type. This type is not 
mandatory except for deserializing but it is highly recommended to configure it in order to make the library faster.

A type is a generic structure which follow the pattern `type` or `type<option1=value, option2=value, option3=value>`.

## Built-in

The library is shipped with some built-in types:

| Type                                         | Description                          |
| -------------------------------------------- | ------------------------------------ |
| `bool`                                       | Boolean type                         |
| `boolean`                                    | Alias of bool                        |
| `double`                                     | Alias of float                       |
| `float`                                      | Float type                           |
| `numeric`                                    | Alias of float                       |
| `int`                                        | Integer type                         |
| `integer`                                    | Alias of int                         |
| `string`                                     | String type                          |
| `array`                                      | Array type                           |
| `array<value=type>`                          | Array type with typed value          |
| `array<key=type, value=type>`                | Array type with typed key and value  |
| `DateTime`                                   | DateTime type                        |
| `DateTime<format='Y-m-d H:i:d'>`             | DateTime type with format            |
| `DateTime<timezone='Europe/Paris'>`          | DateTime type with timezone          |
| `DateTimeImmutable`                          | DateTimeImmutable type               |
| `DateTimeImmutable<format='Y-m-d H:i:d'>`    | DateTimeImmutable type with format   |
| `DateTimeImmutable<timezone='Europe/Paris'>` | DateTimeImmutable type with timezone |
| `Fully\Qualified\Class\Name`                 | Object type                          |

## Custom

If you want to create your own type for a specific class, you can implement the `Ivory\Serializer\Type\TypeInterface`
or extend the `Ivory\Serializer\Type\AbstractClassType`:

``` php
namespace Acme\Serializer\Type;

use Ivory\Serializer\Type\AbstractClassType;

class AcmeObjectType extends AbstractClassType
{
    protected function serialize($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        // Visit your data
    }
    
    protected function deserialize($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        // Visit your data
    }
}
```

Then, just need to register your type in the serializer:

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

When you will serialize or deserialize an `AcmeObject` or any object which extends it, then, your custom type will be 
triggered. 
