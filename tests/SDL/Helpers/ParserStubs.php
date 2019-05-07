<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Helpers;

use Phplrt\Compiler\Grammar\Reader;
use Phplrt\Exception\ExternalException;
use Phplrt\Io\Exception\NotReadableException;
use Phplrt\Io\File;
use Railt\SDL\Parser\Parser;

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
