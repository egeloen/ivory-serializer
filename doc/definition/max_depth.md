# Max Depth

The max depth allows you limit the depth of the graph traversal for a property and also limit circular references.

## Example

In this example, we only (de)-serialize one level of friends.

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
     * @Serializer\MaxDepth(2)
     *
     * @var User[]
     */
    publiic $friends = [];
}
```

## Usage

``` php
use Acme\User;
use Ivory\Serializer\Context\Context;
use Ivory\Serializer\Exclusion\MapDepthExclusionStrategy;

$friend2 = new User();
$friend2->username = 'Ben';

$friend1 = new User();
$friend1->username = 'Tim';
$friend1->friends[] = $friend2;

$user = new User();
$user->username = 'GeLo';
$user->friends[] = $friend1;

$context = new Context();
$context->setExclusionStrategy(new MapDepthExclusionStrategy());

$serialize = $serializer->serialize($user, $format, $context);
// echo $serialize;

$deserialize = $serializer->deserialize($serialize, User::class, $format, $context);
// $deserialize->username === $user->username
// $deserialize->plainPassword === null
```

**If you don't use the max depth exclusion strategy, all properties are (de)-serialized regardless configured max 
depths.**

## Results

### JSON

``` json
{
    "username": "GeLo"
    "friends": [
        {
            "username": "Tim",
            "friends": []
        }
    ]
}
```

### XML

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<result>
    <username>GeLo</username>
    <friends>
        <entry>
            <username>Tim</username>
            <friends />
        </entry>
    </friends>
</result>
```

### YAML

``` yaml
username: GeLo
friends:
    -
        username: Tim
        friends: []
```

### CSV

``` csv
username;friends.0.username;friends.0.friends
GeLo;Tim;[]
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
