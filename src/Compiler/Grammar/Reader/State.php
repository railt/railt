<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Io\Readable;

/**
 * Interface State
 */
interface State
{
    /**
     * @param Readable $grammar
     * @param array $token
     */
    public function resolve(Readable $grammar, array $token): void;

    /**
     * @return iterable
     */
    public function getData(): iterable;
}
