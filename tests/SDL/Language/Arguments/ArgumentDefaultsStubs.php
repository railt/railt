<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Arguments;

use Railt\Component\Io\File;
use Railt\Tests\SDL\Helpers\CompilerStubs;

/**
 * Trait CoercionStubs
 */
trait ArgumentDefaultsStubs
{
    use CompilerStubs;

    /**
     * @param iterable $arguments
     * @param string $pattern
     * @return \Traversable
     * @throws \Exception
     */
    protected function scalarArgumentsDataProvider(iterable $arguments, string $pattern): \Traversable
    {
        foreach ($arguments as $argument) {
            $sources = File::fromSources(\sprintf(
                'enum TestEnum { Red, Green, Blue }' . "\n" .
                'input TestInput { a: Float }' . "\n" .
                'input TestInputWithDefault { a: Int = 42 }' . "\n" .
                $pattern,
                $argument
            ));

            foreach ($this->getCompilers() as $compiler) {
                yield $sources => $compiler;
            }
        }
    }

    /**
     * @return iterable
     */
    private function getArgumentDefaults(): iterable
    {
        yield 'String'   => '"String"';
        yield 'String'   => '"""' . "\n" . 'Multiline String' . "\n" . '"""';

        yield 'DateTime' => '"2018-01-15 08:23:03"';
        yield 'DateTime' => '"08:23:03"';
        yield 'DateTime' => '"2018-01-15"';
        yield 'DateTime' => '"2002-10-02T10:00:00-05:00"';
        yield 'DateTime' => '"2002-10-02T15:00:00.05Z"';

        yield 'Float'    => '0.23';
        yield 'Float'    => '-0.23';

        yield 'Int'      => '-23';
        yield 'Int'      => '42';

        yield 'ID'       => '2';
        yield 'ID'       => '"2"';

        yield 'Boolean'  => 'true';
        yield 'Boolean'  => 'false';

        yield 'Any'      => '"String"';
        yield 'Any'      => '"2018-01-15 08:23:03"';
        yield 'Any'      => '0.23';
        yield 'Any'      => '2';
        yield 'Any'      => '42';

        // Enum: "enum TestEnum { Red, Green, Blue }"
        yield 'TestEnum' => 'Red';
        yield 'TestEnum' => '"Red"';

        // Input:
        // "input TestInput { a: Int }"
        // "input TestInputWithDefault { a: Int = 42 }"

        yield 'TestInput'=> '{a: 42.0}';
        yield 'TestInputWithDefault'=> '{a: 66}';
        yield 'TestInputWithDefault'=> '{}';
    }

    /**
     * @return array
     */
    protected function getPositiveArguments(): iterable
    {
        foreach ($this->getArgumentDefaults() as $arg => $value) {
            yield $arg . ' = ' . $value;
            yield $arg . '! = ' . $value;
            yield $arg . ' = null';

            // Initialized by null
            yield '[' . $arg . ']  = null';
            yield '[' . $arg . '!] = null';
            yield '[' . $arg . ']! = [null]';

            // Type coercion
            // Must be converted to "= [null]"
            yield '[' . $arg . ']! = null';

            // List defaults
            yield '[' . $arg . '!]  = [' . $value . ']';
            yield '[' . $arg . ']!  = [' . $value . ']';
            yield '[' . $arg . '!]! = [' . $value . ']';
        }
    }

    /**
     * @return array
     */
    protected function getNegativeArguments(): iterable
    {
        foreach ($this->getArgumentDefaults() as $arg => $value) {
            // X! = null
            yield $arg . '! = null';

            // X = []
            yield $arg . ' = []';

            // [X!] = [..., NULL, ...]
            yield '[' . $arg . '!] = [null]';
            yield '[' . $arg . '!] = [' . $value . ', null]';
            yield '[' . $arg . '!] = [null, ' . $value . ']';
            yield '[' . $arg . '!] = [' . $value . ', null, ' . $value . ']';

            if ($arg !== 'Any') {
                yield $arg . ' = {a: "Example"}';
                yield $arg . ' = {b: 23}';
            }
        }
    }

    /**
     * @param \Closure $execution
     * @param string $suffix
     * @return void
     * @throws \LogicException
     */
    protected function positiveTestWrapper(\Closure $execution, string $suffix): void
    {
        try {
            $execution();
            $this->assertTrue(true);
        } catch (\Throwable $e) {
            throw new \LogicException(
                (string)$e->getMessage() . "\n" .
                'Should be successful:' . "\n" .
                $suffix . "\n" .
                \str_repeat('-', 60) . "\n"
            );
        }
    }

    /**
     * @param \Closure $execution
     * @param string $suffix
     * @param string $error
     * @return void
     */
    protected function negativeTestWrapper(\Closure $execution, string $suffix, string $error = \Throwable::class): void
    {
        try {
            $execution();
            $this->assertFalse(true,
                'Should throw an error:' . "\n" .
                $suffix . "\n" .
                \str_repeat('-', 60) . "\n"
            );
        } catch (\Throwable $e) {
            $this->assertInstanceOf($error, $e,
                'Error must be an instance of ' . $error . ' but ' . \get_class($e) . ' given:' . "\n" .
                (string)$e . "\n" .
                $suffix . "\n" .
                \str_repeat('-', 60) . "\n"
            );
        }
    }
}
