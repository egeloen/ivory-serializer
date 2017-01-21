# Type

The type allows you to configure a property type. You can find the list of supported types [here](/doc/type.md).

## Example

In this example, we configure the type string on the username property.

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

class User
{
    /**
     * @Serializer\Type("string")
     */
    public $username;
}
```

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

## Results

### JSON

``` json
{
    "login": "GeLo"
}
```

### XML

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<result>
    <login>GeLo</login>
</result>
```

### YAML

``` yaml
login: GeLo
```

### CSV

``` csv
login
GeLo
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
    <class name="Acme\User">
        <property name="username" type="string" />
    </class>
</serializer>
```

### YAML

``` yaml
Acme\User:
    properties:
        username:
            type: string
```

### JSON

``` json
{
    "Acme\\User": {
        "properties": {
            "username": {
                "type": "string"
            }
        }
    }
}
```
