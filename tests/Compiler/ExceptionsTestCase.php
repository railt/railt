<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Compiler;

use Railgun\Exceptions\CompilerException;
use Railgun\Exceptions\NotReadableException;
use Railgun\Tests\AbstractTestCase;

/**
 * Class ExceptionsTestCase
 * @package Railgun\Compiler
 */
class ExceptionsTestCase extends AbstractTestCase
{
    /**
     * @throws \Railgun\Exceptions\UnexpectedTokenException
     * @throws \Railgun\Exceptions\UnrecognizedTokenException
     * @throws \Railgun\Exceptions\CompilerException
     * @throws \Railgun\Exceptions\NotReadableException
     */
    public function testCompilerException(): void
    {
        $this->expectException(CompilerException::class);

        $resource = $this->resource('exceptions/bad.grammar.pp');

        $compiler = new Compiler($resource);
        $compiler->parse($this->file('exceptions/bad.grammar.pp'));
    }

    /**
     * @throws \Railgun\Exceptions\CompilerException
     * @throws \Railgun\Exceptions\UnexpectedTokenException
     * @throws \Railgun\Exceptions\UnrecognizedTokenException
     * @throws \Railgun\Exceptions\NotReadableException
     */
    public function testNotReadableException(): void
    {
        $this->expectException(NotReadableException::class);

        $compiler = new Compiler();
        $compiler->parse($this->file('invalid_file.php'));
    }
}
