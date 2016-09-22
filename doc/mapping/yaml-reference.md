# YAML Reference

``` yaml
Acme\Model\User:
    exclusion_policy: all
    properties:
        username:
            alias: user_name
            type: string
            expose: true
            groups: [group1, group2]
            since: "1.0"
            until: "2.0"
        friends:
            type: array<Acme\Model\User>
            exclude: true
            max_depth: 2
```
