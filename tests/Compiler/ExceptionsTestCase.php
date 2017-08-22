<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Railt\Exceptions\CompilerException;
use Railt\Exceptions\NotReadableException;
use Railt\Tests\AbstractTestCase;

/**
 * Class ExceptionsTestCase
 * @package Railt\Compiler
 */
class ExceptionsTestCase extends AbstractTestCase
{
    /**
     * @throws \Railt\Exceptions\UnexpectedTokenException
     * @throws \Railt\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Exceptions\CompilerException
     * @throws \Railt\Exceptions\NotReadableException
     */
    public function testCompilerException(): void
    {
        $this->expectException(CompilerException::class);

        $resource = $this->resource('exceptions/bad.grammar.pp');

        $compiler = new Compiler($resource);
        $compiler->parse($this->file('exceptions/bad.grammar.pp'));
    }

    /**
     * @throws \Railt\Exceptions\CompilerException
     * @throws \Railt\Exceptions\UnexpectedTokenException
     * @throws \Railt\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Exceptions\NotReadableException
     */
    public function testNotReadableException(): void
    {
        $this->expectException(NotReadableException::class);

        $compiler = new Compiler();
        $compiler->parse($this->file('invalid_file.php'));
    }
}
