# YAML Reference

``` yaml
Acme\Model\User:
    properties:
        username:
            type: string
            groups: [group1, group2]
            since: "1.0"
            until: "2.0"
        friends:
            type: array<Acme\Model\User>
            max_depth: 2
```
