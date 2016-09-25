# YAML Mapping

``` yaml
Acme\Model\User:
    exclusion_policy: all
    order: ["friends", "username"]
    properties:
        username:
            alias: user_name
            type: string
            expose: true
            accessor: getUsername
            mutator: setUsername
            groups: [group1, group2]
            since: "1.0"
            until: "2.0"
        friends:
            type: array<Acme\Model\User>
            exclude: true
            max_depth: 2
```

If you want to learn more about each properties, you can read this [documentation](/doc/mapping/mapping.md).
