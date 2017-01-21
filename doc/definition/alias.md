# Alias

The alias allows you rename a property.

## Example

In this example, we rename the username property to login.

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

class User
{
    /**
     * @Serializer\Alias("login")
     *
     * @var string
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
        <property name="username" alias="login" />
    </class>
</serializer>
```

### YAML

``` yaml
Acme\User:
    properties:
        username:
            alias: login
```

### JSON

``` json
{
    "Acme\\User": {
        "properties": {
            "username": {
                "alias": "login"
            }
        }
    }
}
```
