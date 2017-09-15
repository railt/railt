<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Parser;

use Railt\Parser\Parser;
use Railt\Support\Exceptions\NotFoundException;
use Railt\Tests\AbstractTestCase;
use Railt\Parser\Exceptions\InitializationException;

/**
 * Class ExceptionsTestCase
 * @package Railt\Compiler
 */
class ExceptionsTestCase extends AbstractTestCase
{
    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Parser\Exceptions\ParsingException
     */
    public function testParserException(): void
    {
        $this->expectException(InitializationException::class);

        $resource = $this->resource('exceptions/bad.grammar.pp');

        $parser = new class($resource) extends Parser\SDLParser {
            private $resource;
            public function __construct(string $resource)
            {
                $this->resource = $resource;
                parent::__construct();
            }
            protected function getGrammarFile(): string
            {
                return $this->resource;
            }
        };

        $parser->parse($this->file('exceptions/bad.grammar.pp'));
    }

    /**
     * @throws NotFoundException
     * @throws \PHPUnit\Framework\Exception
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     * @throws \Railt\Parser\Exceptions\ParsingException
     */
    public function testNotReadableException(): void
    {
        $this->expectException(NotFoundException::class);

        $compiler = new Parser();
        $compiler->parse($this->file('invalid_file.php'));
    }
}
