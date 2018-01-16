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
use Railt\SDL\Exceptions\TypeConflictException;
use Railt\Tests\SDL\Language\AbstractLanguageTestCase;

/**
 * Class ObjectArgumentsCoercionTestCase
 */
class ObjectArgumentsDefaultsTestCase extends AbstractLanguageTestCase
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
            'type Example { field(arg: %s): String }'
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
            'type Example { field(arg: %s): String }'
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
