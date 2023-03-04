# Deptrac Extras

So far just one extra collector for Deptrac.

## ClassLikeDocCollector

This can be used to pattern match against the PHPDoc for a class-like.

For example:

```yaml
deptrac:
  layers:
    - name: MyPackage
      collectors:
        - type: ARiddlestone\DeptracExtras\Layer\Collector\ClassLikeDocCollector
          value: @package MyPackage$
```

The value is a regular expression. In the example above the `$` regex line ending is added to avoid matching "@package
MyPackageExtension" for example.
