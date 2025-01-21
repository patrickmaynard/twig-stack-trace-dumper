Stack Trace Dumper Twig Extension
================

This Twig extension adds the trace() function to Twig. 

It will output a string containing a stack trace.

I recommend only using it as a dev dependency. Using it in a production environment is probably not a good idea.

I created this tool for use in an environment where neither the Symfony toolbar nor XDebug was available.

Assuming you *are* able to use those better tools, I would urge you to do so.

## Installation

To install this tool as a dev dependency:

```
composer require --dev patrick-maynard/twig-stack-trace-dumper
```

## Configuration and usage

You'll then need to configure your app to have access to the extension.

Assuming you're using Symfony, you can do that using the instructions here: 
https://symfony.com/doc/current/templating/twig_extension.html#register-an-extension-as-a-service

Once that's done, you can use the new function in Twig files like this:

```
{{ trace()|raw }}
```

UPDATE: As of 2025, the Symfony documentation is pretty flippant about what you need to do to register your extension, essentially saying that it will be registered via magic. This is not always reliable, so you may or may not need to add something like the following to your `services.yaml` file: 

```
    PatrickMaynard\TwigExtensions\Trace\StackTraceDumperExtension:
        tags: ['twig.extension']
```


UNTESTED: If you want a little more detail than `{{ trace()|raw }}` provides, you could also try using `{{ trace_via_debug_backtrace()|raw }}` to get different formatting and some extra information. I haven't yet tested this. If you get a chance to make it work and write a test, please open a pull request. 

Happy debugging!
