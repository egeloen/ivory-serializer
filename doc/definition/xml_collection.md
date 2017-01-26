# XML Collection

The XML collection is dedicated to XML and allow you to control how a collection is (de)-serialized in XML.

## Example

In this example, we configure the roles property.

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

class User
{
    /**
     * @Serializer\XmlCollection({
     *     entry = "entry",
     *     entryAttribute = "key",
     *     keyAsNode = true,
     *     keyAsAttibute = false,
     *     inline = false
     * })
     *
     * @var string[]
     */
    public $roles = [];
}
```

## Usage

``` php
use Acme\User;

$user = new User();
$user->roles[] = 'reader';
$user->roles[] = 'writer';

$serialize = $serializer->serialize($user, $format);
// echo $serialize;

$deserialize = $serializer->deserialize($serialize, User::class, $format);
// $deserialize == $user
```

## Results

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<result>
    <roles>
        <entry key="0">reader</entry>
        <entry key="1">writer</entry>
    </roles>
</result>
```

### Entry

The entry option allows you to configure the node name of each entry of the collection (default "entry").

### Entry Attribute

The entry attribute option allows you to configure the name of each entry attribute of the collection (default "key").

### Key As Node

The key as node option allows you to configure if each keys of the collection should put the as node. If the key is 
not valid, then the key will be put as attribute using the entry attribute option.

### Key As Attribute

The key as attribute option allows you to configure if each keys of the collection should put as attribute using the 
entry attribute option.

### Inline

The inline option allows you to configure if the collection should be inlined. 

``` php
namespace Acme;

use Ivory\Serializer\Mapping\Annotation as Serializer;

class User
{
    /**
     * @Serializer\XmlCollection({
     *     entry = "role", 
     *     inline = true
     * })
     *
     * @var string[]
     */
    public $roles = [];
}
```

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<result>
    <role>reader</role>
    <role>writer</role>
</result>
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
        <property 
            name="roles" 
            xml-entry="entry"
            xml-entry-attribute="key"
            xml-key-as-node="true"
            xml-key-as-attribute="false"
            xml-inline="false" 
        />
    </class>
</serializer>
```

### YAML

``` yaml
Acme\User:
    properties:
        roles:
            xml_entry: entry
            xml_entry_attribute: key
            xml_key_as_node: true
            xml_key_as_attribute: false
            xml_inline: false
```

### JSON

``` json
{
    "Acme\\User": {
        "properties": {
            "username": {
                "xml_entry": "entry",
                "xml_entry_attribute": "key",
                "xml_key_as_node": true,
                "xml_key_as_attribute": false,
                "xml_inline": false
            }
        }
    }
}
```


