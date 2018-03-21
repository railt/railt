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
use Railt\Io\File;
use Railt\SDL\Parser\Factory;
use Railt\SDL\Parser\SchemaParser;

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
        //yield (new Factory())->setParser(new SchemaParser());

        yield (new Factory())->setParser(
            Parser::fromGrammar(File::fromPathname(Factory::GRAMMAR_FILE))
        );
    }
}
