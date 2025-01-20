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
{{ trace() }}
```

UNTESTED: You could also try using `{{ trace_via_debug_backtrace() }}` to get different formatting. I haven't yet tested this. 

Happy debugging!
