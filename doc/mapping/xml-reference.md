# XML Reference

``` xml
<?xml version="1.0" ?>

<serializer
    xmlns="http://egeloen.fr/schema/ivory-serializer"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://egeloen.fr/schema/ivory-serializer
                        http://egeloen.fr/schema/ivory-serializer/mapping-1.0.xsd"
>
    <class name="Acme\Model\User" exclusion-policy="all">
        <property 
            name="username" 
            alias="identifier" 
            type="string"
            expose="true" 
            since="1.0" 
            until="2.0"
        >
            <group>group1</group>
            <group>group2</group>
        </property>
        
        <property 
            name="friends" 
            type="array&lt;Acme\Model\User&gt;>" 
            exclude="true" 
            max-depth="2" 
        />
    </class>
</serializer>
```
