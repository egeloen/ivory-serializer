# Readable

The readable allows you configure if a property is serializable (by default, it is). This property is useful if you 
want to not serialize a property but still de-serialize it.

## Example

In this example, we configure the plainPassword property to not be serializable.

### Property Readable

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
     * @Serializer\Readable(false)
     *
     * @var string|null
     */
    public $plainPassword;
}
```

### Class Readable

By default, all properties are readable but you can alter this behavior by putting the readable option directly on the 
class: 

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @Serializer\Readable(false)
 */
class User
{
    /**
     * @Serializer\Readable(true)
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

## Results

### JSON

``` json
{
    "username": "GeLo"
}
```

### XML

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<result>
    <username>GeLo</username>
</result>
```

### YAML

``` yaml
username: GeLo
```

### CSV

``` csv
username
GeLo
```

## Definitions

If you prefer use an other definition format, the following examples are identical. 

### XML

#### Property Readable

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
        <property name="plainPassword" readable="false" />
    </class>
</serializer>
```

#### Class Readable

``` xml
<?xml version="1.0" encoding="UTF-8" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\User" readable="false">
        <property name="username" readable="true" />
        <property name="plainPassword" />
    </class>
</serializer>
```

### YAML

#### Property Readable

``` yaml
Acme\User:
    properties:
        username: ~
        plainPassword:
            readable: false
```

#### Class Readable

``` yaml
Acme\User:
    readable: false
    properties:
        username:
            readable: true
        plainPassword: ~
```

### JSON

#### Property Readable

``` json
{
    "Acme\\User": {
        "properties": {
            "username": {},
            "plainPassword": {
                "readable": false
            }
        }
    }
}
```

#### Class Readable

``` json
{
    "Acme\\User": {
        "readable": false,
        "properties": {
            "username": {
                "reaadable": true
            },
            "plainPassword": {}
        }
    }
}
```
