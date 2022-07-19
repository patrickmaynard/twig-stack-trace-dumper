Stack Trace Dumper Twig Extension
================

This Twig extension adds the trace() function to Twig. 

It will output a string containing a stack trace.

I recommend only using it as a dev dependency. Using in a production environment is probably not a good idea.

I created this tool for use in an environment where neither the Symfony toolbar nor XDebug was available.

Assuming you *are* able to use those better tools, I would urge you to do so.

To install this tool as a dev dependency:

```
composer require --dev patrick-maynard/twig-stack-trace-dumper
```

You can use the new function in Twig files like this:

```
{{ trace() }}
```

Happy debugging!