<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Driver;

use Railt\Io\Readable;
use Railt\Lexer\LexerInterface;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\GrammarInterface;
use Railt\Parser\ParserInterface;

/**
 * Class Proxy
 */
class Proxy implements ParserInterface
{
    /**
     * @var ParserInterface
     */
    private $parent;

    /**
     * Proxy constructor.
     * @param ParserInterface $parent
     */
    public function __construct(ParserInterface $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return LexerInterface
     */
    public function getLexer(): LexerInterface
    {
        return $this->parent->getLexer();
    }

    /**
     * @return GrammarInterface
     */
    public function getGrammar(): GrammarInterface
    {
        return $this->parent->getGrammar();
    }

    /**
     * @param Readable $input
     * @return iterable
     */
    public function trace(Readable $input): iterable
    {
        return $this->parent->trace($input);
    }

    /**
     * @param Readable $input
     * @return RuleInterface
     */
    public function parse(Readable $input): RuleInterface
    {
        return $this->parent->parse($input);
    }
}
