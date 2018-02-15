<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer;

use Railt\Io\Readable;
use Railt\Lexer\Stream\Stream;

/**
 * Interface LexerInterface
 */
interface LexerInterface
{
    /**
     * @param Readable $input
     * @return Stream
     */
    public function read(Readable $input): Stream;
}
