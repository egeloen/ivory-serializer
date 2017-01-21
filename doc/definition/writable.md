# Writable

The writable allows you configure if a property is de-serializable (by default, it is). This property is useful if you 
want to not de-serialize a property but still serialize it.

## Example

In this example, we configure the plainPassword property to not be de-serializable.

### Property Writable

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

class User
{
    /**
     * @var string
     */
    public $username;
    
    /**
     * @Serializer\Writable(false)
     *
     * @var string|null
     */
    public $plainPassword;
}
```

### Class Writable

By default, all properties are writable but you can alter this behavior by putting the writable option directly on the 
class: 

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @Serializer\Writable(false)
 */
class User
{
    /**
     * @Serializer\Writable(true)
     *
     * @var string
     */
    public $username;
    
    /**
     * @var string|null
     */
    public $plainPassword;
}
```

## Usage

``` php
use Acme\User;

$user = new User();
$user->username = 'GeLo';
user->plainPassword = 'azerty';

$serialize = $serializer->serialize($user, $format);
// echo $serialize;

$deserialize = $serializer->deserialize($serialize, User::class, $format);
// $deserialize->username === $user->username
// $deserialize->plainPassword === null
```

## Definitions

If you prefer use an other definition format, the following examples are identical. 

### XML

#### Property Writable

``` xml
<?xml version="1.0" encoding="UTF-8" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\User">
        <property name="username" />
        <property name="plainPassword" writable="false" />
    </class>
</serializer>
```

#### Class Writable

``` xml
<?xml version="1.0" encoding="UTF-8" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\User" writable="false">
        <property name="username" writable="true" />
        <property name="plainPassword" />
    </class>
</serializer>
```

### YAML

#### Property Writable

``` yaml
Acme\User:
    properties:
        username: ~
        plainPassword:
            writable: false
```

#### Class Writable

``` yaml
Acme\User:
    writable: false
    properties:
        username:
            writable: true
        plainPassword: ~
```

### JSON

#### Property Writable

``` json
{
    "Acme\\User": {
        "properties": {
            "username": {},
            "plainPassword": {
                "writable": false
            }
        }
    }
}
```

#### Class Writable

``` json
{
    "Acme\\User": {
        "writable": false,
        "properties": {
            "username": {
                "reaadable": true
            },
            "plainPassword": {}
        }
    }
}
```
