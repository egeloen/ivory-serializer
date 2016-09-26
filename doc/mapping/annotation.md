# Annotation Mapping

``` php
namespace Acme\Model;

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\Order({"friends", "username"})
 */
class User
{
    /**
     * @Serializer\Expose
     * @Serializer\Alias("user_name")
     * @Serializer\Type("string")
     * @Serializer\Readable(true)
     * @Serializer\Writable(false)
     * @Serializer\Accessor("getUsername")
     * @Serializer\Mutator("setUsername")
     * @Serializer\Groups({"group1", "group2"})
     * @Serializer\Since("1.0")
     * @Serializer\Until("2.0")
     *
     * @var string
     */
    private $username;
    
    /**
     * @Serializer\Exclude
     *
     * @var string
     */
    public $password;
    
    /**
     * @Serializer\Expose
     * @Serializer\Type("array<key=int, value=Acme\Model\User>")
     * @Serializer\MaxDepth(2)
     *
     * @var User[]
     */
    public $friends = [];
    
    /**
     * @return string
     */
    public function getUsername()
    {
        return trim($this->username);
    }
    
    /**
     * @param string username
     */
    public function setUsername($username)
    {
        $this->username = trim($username);
    }
}
```

Here you can find all available annotations directly configured on public properties. Obviously, it also works if you 
encapsulate your data with getter/setter... You can also put annotations on methods. 

Additionally, if you want to learn more about each annotations, you can read this [documentation](/doc/mapping/mapping.md).
