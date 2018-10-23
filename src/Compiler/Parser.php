<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Railt\Compiler\Grammar\Reader;
use Railt\Compiler\Parser\Runtime;
use Railt\Io\Readable;

/**
 * Class Parser
 */
class Parser extends Runtime
{
    /**
     * @param Readable $grammar
     * @return ParserInterface
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public static function fromGrammar(Readable $grammar): ParserInterface
    {
        return (new Reader())->read($grammar)->getParser();
    }
}
