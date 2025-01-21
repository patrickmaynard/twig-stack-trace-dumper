<?php declare(strict_types=1);

namespace PatrickMaynard\TwigExtensions\Trace;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StackTraceDumperExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('trace', [$this, 'returnStackTraceAsString']),
            new TwigFunction('trace_via_debug_backtrace', [$this, 'returnStackTraceAsArrayString']),
        ];
    }

    public function returnStackTraceAsString(): string
    {
        $unthrownException = new \Exception();
        return PHP_EOL . '<pre>' . PHP_EOL . $unthrownException->getTraceAsString() . PHP_EOL . '</pre>' . PHP_EOL;
    }

    public function returnStackTraceAsArrayString(): string 
    {
        return '<pre>' . var_dump(debug_backtrace(), true) . '<.pre>' . PHP_EOL;
    } 
}
