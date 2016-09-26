# JSON Mapping 

``` json
{
    "Acme\\Model\\User": {
        "exclusion_policy": "all",
        "order": ["friends", "username"],
        "properties": {
            "username": {
                "alias": "user_name",
                "type": "string",
                "expose": true,
                "readable": true,
                "writable": false,
                "accessor": "getUsername",
                "mutator": "setUsername",
                "groups": ["group1", "group2"],
                "since": "1.0",
                "until": "2.0"
            },
            "password": {
                "exclude": true
            },
            "friends": {
                "type": "array<key=int, value=Acme\Model\User>",
                "expose": true,
                "max_depth": 2
            }
        }
    }
}
```

If you want to learn more about each properties, you can read this [documentation](/doc/mapping/mapping.md).
