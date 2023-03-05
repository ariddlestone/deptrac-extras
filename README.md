# Deptrac Extras

So far just one extra collector for Deptrac.

## ClassLikeDocCollector

This can be used to pattern match against the PHPDoc for a class-like.

For example:

```yaml
services:
  - class: ARiddlestone\DeptracExtras\Layer\Collector\ClassLikeDocCollector
    autowire: true
    tags:
      - { name: collector, type: classLikeDoc }

deptrac:
  layers:
    - name: MyPackage
      collectors:
        - type: classLikeDoc
          value: @package MyPackage\\s+$
```

The value is a regular expression. In the example above the `\\s+$` regex line ending is added to avoid matching
"@package MyPackageExtension" for example.
