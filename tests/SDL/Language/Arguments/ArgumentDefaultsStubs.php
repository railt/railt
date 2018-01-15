<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Arguments;

use Railt\Io\File;
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
            $sources = File::fromSources(\sprintf($pattern, $argument));

            foreach ($this->getCompilers() as $compiler) {
                yield $sources => $compiler;
            }
        }
    }

    /**
     * @return iterable
     */
    private function getScalarDefaults(): iterable
    {
        yield 'String'   => '"String"';
        yield 'String'   => '"""' . "\n" . 'Multiline String' . "\n" . '"""';

        yield 'DateTime' => '"2018-01-15 08:23:03"';
        yield 'DateTime' => '"08:23:03"';
        yield 'DateTime' => '"2018-01-15"';
        yield 'DateTime' => '"2002-10-02T10:00:00-05:00"';
        yield 'DateTime' => '"2002-10-02T15:00:00.05Z"';

        yield 'Float'    => '0.23';
        yield 'Float'    => '.42';
        yield 'Float'    => '-.023';

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
    }

    /**
     * @return array
     */
    protected function getPositiveScalarArguments(): iterable
    {
        foreach ($this->getScalarDefaults() as $scalar => $value) {
            yield $scalar . ' = ' . $value;
            yield $scalar . '! = ' . $value;
            yield $scalar . ' = null';

            // Initialized by null
            yield '[' . $scalar . ']  = null';
            yield '[' . $scalar . '!] = null';
            yield '[' . $scalar . ']! = [null]';

            // Type coercion
            // Must be converted to "= [null]"
            yield '[' . $scalar . ']! = null';

            // List defaults
            yield '[' . $scalar . '!]  = [' . $value . ']';
            yield '[' . $scalar . ']!  = [' . $value . ']';
            yield '[' . $scalar . '!]! = [' . $value . ']';
        }
    }

    /**
     * @return array
     */
    protected function getNegativeScalarArguments(): iterable
    {
        foreach ($this->getScalarDefaults() as $scalar => $value) {
            // X! = null
            yield $scalar . '! = null';

            // X = []
            yield $scalar . ' = []';

            // [X!] = [..., NULL, ...]
            yield '[' . $scalar . '!] = [null]';
            yield '[' . $scalar . '!] = [' . $value . ', null]';
            yield '[' . $scalar . '!] = [null, ' . $value . ']';
            yield '[' . $scalar . '!] = [' . $value . ', null, ' . $value . ']';
        }
    }
}
