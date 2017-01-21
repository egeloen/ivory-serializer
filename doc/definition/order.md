# Order

The order allows you to order properties.

## Example

In this example, we configure a custom ordering for the User class. The order supports custom ordering or alphabetical 
ordering via `ASC` or `DESC`.

### Alphabetical Ordering

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @Serializer\Order("ASC")
 */
class User
{
    /**
     * @var string
     */
    public $username;
    
    /**
     * @var bool
     */
    public $active;
}
```

### Custom Ordering

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @Serializer\Order("active,username")
 */
class User
{
    /**
     * @var string
     */
    public $username;
    
    /**
     * @var bool
     */
    public $active;
}
```

## Usage

``` php
use Acme\User;

$user = new User();
$user->username = 'GeLo';
user->active = true;

$serialize = $serializer->serialize($user, $format);
// echo $serialize;

$deserialize = $serializer->deserialize($serialize, User::class, $format);
// $deserialize == $user
```

## Results

### JSON

``` json
{
    "active": true,
    "username": "GeLo"
}
```

### XML

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<result>
    <active>true</active>
    <username>GeLo</username>
</result>
```

### YAML

``` yaml
active: true
username: GeLo
```

### CSV

``` csv
active;username
true;GeLo
```

## Definitions

If you prefer use an other definition format, the following examples are identical. 

### XML

#### Alphabetical Ordering

``` xml
<?xml version="1.0" encoding="UTF-8" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\User" order="ASC">
        <property name="username" />
        <property name="active" />
    </class>
</serializer>
```

#### Custom Ordering

``` xml
<?xml version="1.0" encoding="UTF-8" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\User" order="active,username">
        <property name="username" />
        <property name="active" />
    </class>
</serializer>
```

### YAML

#### Alphabetical Ordering

``` yaml
Acme\User:
    order: "ASC"
    properties:
        username: ~
        active: ~
```

#### Custom Ordering

``` yaml
Acme\User:
    order: ["active", "username"]
    properties:
        username: ~
        active: ~
```

### JSON

#### Alphabetical Ordering

``` json
{
    "Acme\\User": {
        "order": "ASC",
        "properties": {
            "username": {},
            "active": {}
        }
    }
}
```

#### Custom Ordering

``` json
{
    "Acme\\User": {
        "order": ["active", "username"],
        "properties": {
            "username": {},
            "active": {}
        }
    }
}
```
