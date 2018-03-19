<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Railt\Compiler\Parser\Ast\NodeInterface;
use Railt\Io\Readable;

/**
 * Interface ParserInterface
 */
interface ParserInterface
{
    /**
     * @return LexerInterface
     */
    public function getLexer(): LexerInterface;

    /**
     * @param Readable $input
     * @return \Traversable|NodeInterface
     */
    public function parse(Readable $input): NodeInterface;
}
