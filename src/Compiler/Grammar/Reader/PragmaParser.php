<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\Grammar\Lexer\GrammarToken;
use Railt\Compiler\Grammar\Reader;
use Railt\Compiler\Lexer\Token;
use Railt\Io\Readable;

/**
 * Class PragmaRule
 */
class PragmaParser implements Step
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var array
     */
    private $pragmas = [];

    /**
     * TokenRule constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param Token $token
     * @return bool
     */
    public function match(Token $token): bool
    {
        return $token->is(GrammarToken::T_PRAGMA);
    }

    /**
     * @param Readable $file
     * @param Token $token
     */
    public function parse(Readable $file, Token $token): void
    {
        $this->pragmas[$token->get(0)] = $token->get(1);
    }
}
