<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Parser;

use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Parser;
use Hoa\Compiler\Llk\Parser as LlkParser;
use Hoa\File\Read;
use Hoa\Stream\IStream\In;

/**
 * Class SDLParser
 * @package Railt\Parser\Parser
 */
class SDLParser extends AbstractParser
{
    /**
     * SDLGrammar file
     */
    protected const GRAPHQL_SDL_GRAMMAR_FILE = __DIR__ . '/../resources/grammar/sdl.pp';

    /**
     * @return Parser
     * @throws \Hoa\Compiler\Exception
     */
    protected function createParser(): LlkParser
    {
        return Llk::load($this->getStream());
    }

    /**
     * @return In
     */
    private function getStream(): In
    {
        return new Read($this->getGrammarFile());
    }

    /**
     * @return string
     */
    protected function getGrammarFile(): string
    {
        return self::GRAPHQL_SDL_GRAMMAR_FILE;
    }
}
