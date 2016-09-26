# XML Mapping

``` xml
<?xml version="1.0" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\Model\User" exclusion-policy="all" order="friends, username">
        <property 
            name="username" 
            alias="identifier" 
            type="string"
            expose="true" 
            readable="true"
            writable="false"
            accessor="getUsername"
            mutator="setUsername"
            since="1.0" 
            until="2.0"
        >
            <group>group1</group>
            <group>group2</group>
        </property>
        
        <property name="password" exclude="true" />
        
        <property 
            name="friends" 
            type="array&lt;Acme\Model\User&gt;>" 
            expose="true" 
            max-depth="2" 
        />
    </class>
</serializer>
```

If you want to learn more about each nodes and attributes, you can read this 
[documentation](/doc/mapping/mapping.md).
