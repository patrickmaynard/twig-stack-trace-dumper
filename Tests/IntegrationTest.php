<?php

namespace PatrickMaynard\TwigExtensions\Trace\Tests;

use PatrickMaynard\TwigExtensions\Trace\StackTraceDumperExtension;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Loader\ArrayLoader;
use Twig\Test\IntegrationTestCase;

class IntegrationTest extends IntegrationTestCase
{
    public function getExtensions()
    {
        return [
            new StackTraceDumperExtension(),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures/';
    }

    protected function doIntegrationTest($file, $message, $condition, $templates, $exception, $outputs, $deprecation = '')
    {
        $loader = new ArrayLoader($templates);

        foreach ($outputs as $i => $match) {
            $config = array_merge([
                'cache' => false,
                'strict_variables' => true,
            ], $match[2] ? eval($match[2].';') : []);
            $twig = new Environment($loader, $config);
            $twig->addGlobal('global', 'global');
            foreach ($this->getRuntimeLoaders() as $runtimeLoader) {
                $twig->addRuntimeLoader($runtimeLoader);
            }

            foreach ($this->getExtensions() as $extension) {
                $twig->addExtension($extension);
            }

            foreach ($this->getTwigFilters() as $filter) {
                $twig->addFilter($filter);
            }

            foreach ($this->getTwigTests() as $test) {
                $twig->addTest($test);
            }

            foreach ($this->getTwigFunctions() as $function) {
                $twig->addFunction($function);
            }

            // avoid using the same PHP class name for different cases
            $p = new \ReflectionProperty($twig, 'templateClassPrefix');
            $p->setAccessible(true);
            $p->setValue($twig, '__TwigTemplate_'.hash(\PHP_VERSION_ID < 80100 ? 'sha256' : 'xxh128', uniqid(mt_rand(), true), false).'_');

            $deprecations = [];
            try {
                $prevHandler = set_error_handler(function ($type, $msg, $file, $line, $context = []) use (&$deprecations, &$prevHandler) {
                    if (\E_USER_DEPRECATED === $type) {
                        $deprecations[] = $msg;

                        return true;
                    }

                    return $prevHandler ? $prevHandler($type, $msg, $file, $line, $context) : false;
                });

                $template = $twig->load('index.twig');
            } catch (\Exception $e) {
                if (false !== $exception) {
                    $message = $e->getMessage();
                    $this->assertSame(trim($exception), trim(sprintf('%s: %s', \get_class($e), $message)));
                    $last = substr($message, \strlen($message) - 1);
                    $this->assertTrue('.' === $last || '?' === $last, 'Exception message must end with a dot or a question mark.');

                    return;
                }

                throw new Error(sprintf('%s: %s', \get_class($e), $e->getMessage()), -1, null, $e);
            } finally {
                restore_error_handler();
            }

            $this->assertSame($deprecation, implode("\n", $deprecations));

            try {
                $output = trim($template->render(eval($match[1].';')), "\n ");
            } catch (\Exception $e) {
                if (false !== $exception) {
                    $this->assertSame(trim($exception), trim(sprintf('%s: %s', \get_class($e), $e->getMessage())));

                    return;
                }

                $e = new Error(sprintf('%s: %s', \get_class($e), $e->getMessage()), -1, null, $e);

                $output = trim(sprintf('%s: %s', \get_class($e), $e->getMessage()));
            }

            if (false !== $exception) {
                list($class) = explode(':', $exception);
                $constraintClass = class_exists('PHPUnit\Framework\Constraint\Exception') ? 'PHPUnit\Framework\Constraint\Exception' : 'PHPUnit_Framework_Constraint_Exception';
                $this->assertThat(null, new $constraintClass($class));
            }

            $expected = trim($match[3], "\n ");

            if (strpos($output, $expected) === false) {
                printf("Compiled templates that failed on case %d:\n", $i + 1);

                foreach (array_keys($templates) as $name) {
                    echo "Template: $name\n";
                    echo $twig->compile($twig->parse($twig->tokenize($twig->getLoader()->getSourceContext($name))));
                }
            }
            self::assertTrue(strpos($output, $expected) !== false);
        }
    }

    /**
     * This isn't really relevant to our situation right now.
     * But it's easier to stub it than to disentangle ourselves from Twig\Test\IntegrationTestCase.
     * So I kept it around as a stub in case we need it later.
     *
     * @dataProvider getLegacyTests
     * @group legacy
     */
    public function testLegacyIntegration($file, $message, $condition, $templates, $exception, $outputs, $deprecation = '')
    {
        self::assertTrue(true);
    }
}
