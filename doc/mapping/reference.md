# Mapping Reference

## Exclusion Policy

The exclusion policy is useful in order to keep control of exposition or exclusion of your class properties. You 
define a policy for a class and then, you configure if a property should be exposed or excluded via respectively the 
expose and exclude mappings.

The available policies are:

 - `none`: All fields are exposed (default).
 - `all`: All fields are excluded.

## Expose

The expose mapping allows you to expose a property when the exclusion policy used is `all`. Doing so will expose the 
property using this mapping.

## Exclude

The exclude mapping allows you to exclude a property when the exclusion policy used is `none`. Doing so will exclude 
the property using this mapping.

## Order

The order mapping allows you to order class properties. The order supports `ASC`, `DESC` or a list of ordered 
properties. If one or more properties are exposed but are not part of the list of ordered properties, then the 
serializer appends then at the end without altering the original order if it is predictable.

## Type

The type mapping allows you to define the type of a property. It is highly recommended to configure it in order to make 
the library fastest. If you want to learn more about types, you can read this [documentation](/doc/type.md).

## Alias

The alias mapping allows you to configure a different name for a property. This new name (alias) will be used when 
serializing and deserializing the property instead of the property name. Be aware that, if a custom naming strategy is 
configured, it will be not be apply on the alias.

## Accessor

The accessor mapping allows you to configure the method to use in order to fetch the data when serializing.

## Mutator

The mutator mapping allows you to configure the method to use in order to set the data when deserializing.

## Groups

The groups mapping allows you to filter the (de)-serialized data based on groups. It gives you the ability to set up 
different layers of your classes really easily. Using groups requires to use a context which is documented 
[here](/doc/context.md). If you don't use it, the groups are ignored.

## Max Depth

The max depth mapping allows you to limit the depth of the graph traversal during the (de)-serialization. Be ware that 
the max depth only concerns real depth steps. Let's take an example:

Given you put a max depth of 2 on an array property. When visiting the property, the serializer will traverse the 
first level of the array (depth = 1), when it will visit one entry of the array, we traverse a level (depth = 2). At 
this state, if the data is a scalar value (not object or array), it is (de)-serialized since we have not exceed our 
limit (2) otherwise it depends... If the data is an array, we traverse it (depth = 3) and the depth becomes greater than
our limit (2), so, the serializer will stop. If the data is an object, we traverse each properties (depth = 3) but 
**the max depth exclusion strategy never excludes class properties**, so, each of them are still (de)-serialized. Then, 
at the next depth level (for example if a property value is an array or an object), the serializer will stop.

In addition to this explanation, the serializer stops differently according to the last data (de)-serialized. If the 
last data is a scalar or object, it stops with `null`, if the data is an array, it stops with an array.
 
Finally, using max depth requires to use a context which is documented [here](/doc/context.md). If you don't use it, 
the max depth is ignored.

## Since

The since mapping allows you to expose a property since a specific version. The since mapping requires to use a context 
which is documented [here](/doc/context.md). If you don't use it, the since mapping is ignored.

## Until

The until mapping allows you to expose a property until a specific version. The until mapping requires to use a context 
which is documented [here](/doc/context.md). If you don't use it, the until mapping is ignored.
