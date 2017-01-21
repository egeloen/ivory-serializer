# Exclusion Policy

The exclusion policy allows you to configure globally if properties are exposed or excluded (by default, exposed).

| Policy         | Description                                                      |
| -------------- | ---------------------------------------------------------------- |
| none (default) | All properties are exposed except properties marked as excluded. |
| all            | No properties are exposed except properties marked as exposed.   |

## Example

In this example, we configure the User class to only expose the username property.

### Expose

``` php
namespace Acme;
        
use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class User
{
    /**
     * @Serializer\Expose
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

### Exclude

``` php
namespace Acme;
        
use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("none")
 */
class User
{
    /**
     * @var string
     */
    public $username = 'GeLo';
    
    /**
     * @Serializer\Exclude
     *
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
$user->plainPassword = 'azerty';

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

#### Expose

``` xml
<?xml version="1.0" encoding="UTF-8" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\User" exclusion-policy="all">
        <property name="username" expose="true" />
        <property name="plainPassword" />
    </class>
</serializer>
```

#### Exclude

``` xml
<?xml version="1.0" encoding="UTF-8" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\User" exclusion-policy="none">
        <property name="username" />
        <property name="plainPassword" exclude="true" />
    </class>
</serializer>
```

### YAML

#### Expose

``` yaml
Acme\User:
    exclusion_policy: all
    properties:
        username:
            expose: true
        plainPassword: ~
```

#### Exclude

``` yaml
Acme\User:
    exclusion_policy: none
    properties:
        username: ~
        plainPassword:
            exclude: true
```

### JSON

#### Expose

``` json
{
    "Acme\\User": {
        "exclusion_policy": "all",
        "properties": {
            "username": {
                "expose": true
            },
            "plainPassword": {}
        }
    }
}
```

#### Exclude

``` json
{
    "Acme\\User": {
        "exclusion_policy": "none",
        "properties": {
            "username": {},
            "plainPassword": {
                "exclude": true
            }
        }
    }
}
```
