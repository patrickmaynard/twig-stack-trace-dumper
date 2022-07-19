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
        ];
    }

    public function returnStackTraceAsString(): string
    {
        $unthrownException = new \Exception();
        return PHP_EOL . '<pre>' . PHP_EOL . $unthrownException->getTraceAsString() . PHP_EOL . '</pre>' . PHP_EOL;
    }
}
