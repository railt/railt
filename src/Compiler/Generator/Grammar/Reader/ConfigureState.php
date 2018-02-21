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
use Railt\Io\Readable;
use Railt\Compiler\Lexer\Tokens\Output;

/**
 * Class ConfigureState
 */
class ConfigureState implements State
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
        $this->pragma->add(...$token[Output::I_TOKEN_CONTEXT]);
    }

    /**
     * @return iterable|Pragma
     */
    public function getData(): iterable
    {
        return $this->pragma;
    }
}
