<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Helpers;

use Railt\Compiler\Parser;
use Railt\Compiler\Runtime;
use Railt\SDL\Parser\Compiled;
use Railt\SDL\Parser\Factory;

/**
 * Trait ParserStubs
 */
trait ParserStubs
{
    /**
     * @return \Traversable|Factory[]
     */
    protected function getParsers(): \Traversable
    {
        yield from $this->wrapParsers(new Factory());
    }

    /**
     * @param Factory $parser
     * @return \Traversable
     */
    protected function wrapParsers(Factory $parser): \Traversable
    {
        $complied = clone $parser;
        $complied->setRuntime(new Compiled());
        yield $complied;

        $runtime = clone $parser;
        $runtime->setRuntime(new Runtime(Factory::getGrammarFile()));
        yield $runtime;
    }
}
