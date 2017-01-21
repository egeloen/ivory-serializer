# XML Root

The XML root is dedicated to XML and allows you to configure the top XML root node name.

## Example

In this example, we configure the XML top root node name for the User class.

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @Serializer\XmlRoot("user")
 */
class User
{
    /**
     * @var string
     */
    public $username;
}
```

**The XML root node is only used for the TOP root node and is not used for collection. If you want to configure the 
collection entry, use the [`XmlCollection`](/doc/definition/xml_collection.md) instead.**

## Usage

``` php
use Acme\User;

$user = new User();
$user->username = 'GeLo';

$serialize = $serializer->serialize($user, $format);
// echo $serialize;

$deserialize = $serializer->deserialize($serialize, User::class, $format);
// $deserialize == $user
```

## Result

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<user>
    <username>GeLo</username>
</user>
```

## Definitions

If you prefer use an other definition format, the following examples are identical. 

### XML

``` xml
<?xml version="1.0" encoding="UTF-8" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\User" xml-root="user">
        <property name="username" />
    </class>
</serializer>
```

### YAML

``` yaml
Acme\User:
    xml_root: user
    properties:
        username: ~
```

### JSON

``` json
{
    "Acme\\User": {
        "xml_root": "user",
        "properties": {
            "username": {}
        }
    }
}
```
