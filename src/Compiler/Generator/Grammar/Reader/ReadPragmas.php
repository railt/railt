<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader;

use Railt\Compiler\Generator\Pragma;
use Railt\Compiler\Lexer\Tokens\Output;
use Railt\Io\Readable;

/**
 * Class ReadPragmas
 */
class ReadPragmas implements State
{
    /**
     * @var Pragma
     */
    private $pragma;

    /**
     * LexingState constructor.
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\InvalidPragmaException
     */
    public function __construct()
    {
        $this->pragma = new Pragma();
    }

    /**
     * @param Readable $grammar
     * @param array $token
     * @throws \Railt\Compiler\Generator\Grammar\Exceptions\InvalidPragmaException
     */
    public function resolve(Readable $grammar, array $token): void
    {
        $this->pragma->add(...$token[Output::T_CONTEXT]);
    }

    /**
     * @return iterable|Pragma
     */
    public function getData(): iterable
    {
        return $this->pragma;
    }
}
