<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler;

use Serafim\Railgun\Exceptions\CompilerException;
use Serafim\Railgun\Exceptions\NotReadableException;
use Serafim\Railgun\Tests\AbstractTestCase;

/**
 * Class ExceptionsTestCase
 * @package Serafim\Railgun\Compiler
 */
class ExceptionsTestCase extends AbstractTestCase
{
    /**
     * @throws \Serafim\Railgun\Exceptions\UnexpectedTokenException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\NotReadableException
     */
    public function testCompilerException(): void
    {
        $this->expectException(CompilerException::class);

        $resource = $this->resource('exceptions/bad.grammar.pp');

        $compiler = new Compiler($resource);
        $compiler->parse($this->file('exceptions/bad.grammar.pp'));
    }

    /**
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\UnexpectedTokenException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     * @throws \Serafim\Railgun\Exceptions\NotReadableException
     */
    public function testNotReadableException(): void
    {
        $this->expectException(NotReadableException::class);

        $compiler = new Compiler();
        $compiler->parse($this->file('invalid_file.php'));
    }
}
