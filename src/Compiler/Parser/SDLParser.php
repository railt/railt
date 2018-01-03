<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser;

use Railt\Compiler\Exceptions\CompilerException;
use Railt\Parser\Io\Readable;
use Railt\Parser\Llk;
use Railt\Parser\Parser as LlkParser;
use Railt\Reflection\Filesystem\File;

/**
 * Class SDLParser
 */
class SDLParser extends AbstractParser
{
    /**
     * SDLGrammar file
     */
    protected const GRAPHQL_SDL_GRAMMAR_FILE = __DIR__ . '/../resources/grammar/sdl.pp';

    /**
     * @return LlkParser
     * @throws \LogicException
     */
    protected function createParser(): LlkParser
    {
        try {
            return (new Llk($this->getGrammar()))->getParser();
        } catch (\Exception $e) {
            throw new CompilerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return Readable
     * @throws \Railt\Reflection\Filesystem\NotReadableException
     */
    private function getGrammar(): Readable
    {
        return File::fromPathname($this->getGrammarFile());
    }

    /**
     * @return string
     */
    protected function getGrammarFile(): string
    {
        return self::GRAPHQL_SDL_GRAMMAR_FILE;
    }
}
