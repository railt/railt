<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language\Arguments;

use Railt\Io\Readable;
use Railt\SDL\Compiler;
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class DirectiveArgumentsCoercionTestCase
 */
class DirectiveArgumentsDefaultsTestCase extends AbstractLanguageTestCase
{
    use ArgumentDefaultsStubs;

    /**
     * @return array
     * @throws \Exception
     */
    public function positiveProvider(): array
    {
        $result = [];

        $data = $this->scalarArgumentsDataProvider(
            $this->getPositiveArguments(),
            'directive @example(arg: %s) on OBJECT'
        );

        foreach ($data as $sources => $compiler) {
            $result[] = [$compiler, $sources];
        }

        return $result;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function negativeProvider(): array
    {
        $result = [];

        $data = $this->scalarArgumentsDataProvider(
            $this->getNegativeArguments(),
            'directive @example(arg: %s) on OBJECT'
        );

        foreach ($data as $sources => $compiler) {
            $result[] = [$compiler, $sources];
        }

        return $result;
    }

    /**
     * @dataProvider positiveProvider
     *
     * @param Compiler $compiler
     * @param Readable $src
     * @return void
     */
    public function testValidDefaultArguments(Compiler $compiler, Readable $src): void
    {
        $this->positiveTestWrapper(function () use ($compiler, $src): void {
            $compiler->compile($src);
        }, $src->getContents());
    }

    /**
     * @dataProvider negativeProvider
     *
     * @param Compiler $compiler
     * @param Readable $src
     * @return void
     */
    public function testInvalidDefaultArguments(Compiler $compiler, Readable $src): void
    {
        $this->negativeTestWrapper(function () use ($compiler, $src): void {
            $compiler->compile($src);
        }, $src->getContents(), TypeConflictException::class);
    }
}
