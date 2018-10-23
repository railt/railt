<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\Grammar\Lexer\Grammar;
use Railt\Compiler\TokenInterface;
use Railt\Io\Readable;

/**
 * Class PragmaRule
 */
class PragmaParser implements Step
{
    /**
     * @var array
     */
    private $pragmas = [];

    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function match(TokenInterface $token): bool
    {
        return $token->name() === Grammar::T_PRAGMA;
    }

    /**
     * @param Readable $file
     * @param TokenInterface $token
     */
    public function parse(Readable $file, TokenInterface $token): void
    {
        $this->pragmas[$token->value(1)] = $token->value(2);
    }
}
