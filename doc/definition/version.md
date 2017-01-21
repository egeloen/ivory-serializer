# Version

The since & until allows you include properties based on the context version provided via the context.

## Example

In this example, we only include the username property since the 1.0.0 version and include the plainPassword until the 
2.0.0 version.

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

class User
{
    /**
     * @Serializer\Since("1.0.0")
     *
     * @var string
     */
    public $username;
    
    /**
     * @Serializer\Until("2.0.0")
     *
     * @var string|null
     */
    publiic $plainPassword;
}
```

## Usage

``` php
use Acme\User;
use Ivory\Serializer\Context\Context;
use Ivory\Serializer\Exclusion\VersionExclusionStrategy;

$user = new User();
$user->username = 'GeLo';
$user->plainPassword = 'azerty';

$context = new Context();
$context->setExclusionStrategy(new VersionExclusionStrategy('2.1.0'));

$serialize = $serializer->serialize($user, $format, $context);
// echo $serialize;

$deserialize = $serializer->deserialize($serialize, User::class, $format, $context);
// $deserialize->username === $user->username
// $deserialize->plainPassword === null
```

**If you don't use the version exclusion strategy, all properties are (de)-serialized regardless configured versions.**

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

``` xml
<?xml version="1.0" encoding="UTF-8" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\User">
        <property name="username" since="1.0.0" />
        <property name="plainPassword" until="2.0.0" />
    </class>
</serializer>
```

### YAML

``` yaml
Acme\User:
    properties:
        username:
            since: "1.0.0"
        plainPassword:
            until: "2.0.0"
```

### JSON

``` json
{
    "Acme\\User": {
        "properties": {
            "username": {
                "since": "1.0.0"
            },
            "plainPassword": {
                "until": "2.0.0"
            }
        }
    }
}
```
