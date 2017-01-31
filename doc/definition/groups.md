# Groups

The groups allows you include properties based on the runtime groups provided via the context. Be aware that if you
don't configure groups, the serializer will automatically use the group `Default` for your property.

## Example

In this example, we only (de)-serialize the username property for groups: group1 or group2.

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

class User
{
    /**
     * @Serializer\Groups(["group1", "group2"])
     *
     * @var string
     */
    public $username;
    
    /**
     * @var string|null
     */
    publiic $plainPassword;
}
```

## Usage

``` php
use Acme\User;
use Ivory\Serializer\Context\Context;
use Ivory\Serializer\Exclusion\GroupsExclusionStrategy;

$user = new User();
$user->username = 'GeLo';
$user->plainPassword = 'azerty';

$context = new Context();
$context->setExclusionStrategy(new GroupsExclusionStrategy(['group1']));

$serialize = $serializer->serialize($user, $format, $context);
// echo $serialize;

$deserialize = $serializer->deserialize($serialize, User::class, $format, $context);
// $deserialize->username === $user->username
// $deserialize->plainPassword === null
```

**If you don't use the groups exclusion strategy, all properties are (de)-serialized regardless configured groups.**

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
        <property name="username" groups="group1,group2" />
        <property name="plainPassword" />
    </class>
</serializer>
```

### YAML

``` yaml
Acme\User:
    properties:
        username:
            groups: [group1, group2]
        plainPassword: ~
```

### JSON

``` json
{
    "Acme\\User": {
        "properties": {
            "username": {
                "groups": ["group1", "group2"]
            },
            "plainPassword": {}
        }
    }
}
```
