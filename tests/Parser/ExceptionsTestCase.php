<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Parser;

use Railt\Parser\Exceptions\NotReadableException;
use Railt\Parser\Exceptions\ParserException;
use Railt\Parser\Parser;
use Railt\Tests\AbstractTestCase;

/**
 * Class ExceptionsTestCase
 * @package Railt\Compiler
 */
class ExceptionsTestCase extends AbstractTestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Parser\Exceptions\ParserException
     * @throws \Railt\Parser\Exceptions\NotReadableException
     */
    public function testParserException(): void
    {
        $this->expectException(ParserException::class);

        $resource = $this->resource('exceptions/bad.grammar.pp');

        $parser = new Parser($resource);
        $parser->parse($this->file('exceptions/bad.grammar.pp'));
    }

    /**
     * @throws NotReadableException
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Parser\Exceptions\ParserException
     */
    public function testNotReadableException(): void
    {
        $this->expectException(NotReadableException::class);

        $compiler = new Parser();
        $compiler->parse($this->file('invalid_file.php'));
    }
}
