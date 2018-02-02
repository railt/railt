<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL;

use Railt\Io\Readable;

/**
 * Class Compiler
 */
class Compiler
{
    /**
     * @param Readable $readable
     * @return mixed|null
     * @throws \Railt\Compiler\Exception\UnexpectedTokenException
     * @throws \Railt\Compiler\Exception\InvalidPragmaException
     * @throws \Railt\Compiler\Exception\Exception
     * @throws \LogicException
     * @throws \Railt\Compiler\Exception\LexerException
     * @throws \Railt\SDL\Exceptions\UnexpectedTokenException
     * @throws \Railt\SDL\Compiler\Exceptions\CompilerException
     */
    public function compile(Readable $readable)
    {

    }
}
