<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\Grammar\Reader;
use Railt\Compiler\Lexer\Token;
use Railt\Io\Readable;

/**
 * Interface Step
 */
interface Step
{
    /**
     * GrammarRule constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader);

    /**
     * @param Token $token
     * @return bool
     */
    public function match(Token $token): bool;

    /**
     * @param Readable $file
     * @param Token $token
     */
    public function parse(Readable $file, Token $token): void;
}
