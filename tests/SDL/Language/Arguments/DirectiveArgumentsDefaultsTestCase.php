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
use Railt\Io\Readable;
use Railt\SDL\Compiler;
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
            $this->getPositiveScalarArguments(),
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
            $this->getNegativeScalarArguments(),
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
    public function testScalarsValidDefaultArguments(Compiler $compiler, Readable $src): void
    {
        try {
            $compiler->compile($src);

            $this->assertTrue(true);
        } catch (\Throwable $e) {
            throw new \LogicException(
                (string)$e->getMessage() . "\n" . 'BUT Should be successful:' . "\n" . $src->getContents()
            );
        }
    }

    /**
     * @dataProvider negativeProvider
     *
     * @param Compiler $compiler
     * @param Readable $src
     * @return void
     */
    public function testScalarsInvalidDefaultArguments(Compiler $compiler, Readable $src): void
    {
        $this->expectException(TypeConflictException::class);

        $compiler->compile($src);
    }
}
