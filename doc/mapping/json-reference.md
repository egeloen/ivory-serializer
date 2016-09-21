# JSON Reference 

``` json
{
    "Acme\Model\User": {
        "properties": {
            "username": {
                "alias": "user_name",
                "type": "string",
                "groups": ["group1", "group2"],
                "since": "1.0",
                "until": "2.0"
            },
            "friends": {
                "type": "array<key=int, value=Acme\Model\User>",
                "max_depth": 2
            }
        }
    }
}
```
