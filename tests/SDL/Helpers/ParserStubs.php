<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Helpers;

use Railt\Component\Io\File;
use Railt\Component\SDL\Parser\Parser;
use Railt\Component\Compiler\Grammar\Reader;
use Railt\Component\Exception\ExternalException;
use Railt\Component\Io\Exception\NotReadableException;

/**
 * Trait ParserStubs
 */
trait ParserStubs
{
    /**
     * @return \Traversable
     * @throws ExternalException
     * @throws NotReadableException
     */
    protected function getParsers(): \Traversable
    {
        yield 'Compiled Parser' => new Parser();

        yield 'Generated Parser' => (new Reader(File::fromPathname(Parser::GRAMMAR_PATHNAME)))->getParser();
    }
}
