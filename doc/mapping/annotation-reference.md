# Annotation Reference

``` php
namespace Acme\Model;

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 */
class User
{
    /**
     * @Serializer\Expose
     * @Serializer\Alias("user_name")
     * @Serializer\Type("string")
     * @Serializer\Groups({"group1", "group2"})
     * @Serializer\Since("1.0")
     * @Serializer\Until("2.0")
     */
    public $username;
    
    /**
     * @Serializer\Exclude
     * @Serializer\Type("array<key=int, value=Acme\Model\User>")
     * @Serializer\MaxDepth(2)
     */
    public $friends = [];
}
```

Here you can find all available annotations directly configured on public properties. Obviously, it also works if you 
encapsulate your data with getter/setter... You can also put annotations on methods. 
