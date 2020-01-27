<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Frontend;

use Phplrt\Contracts\Grammar\RuleInterface;
use Phplrt\Contracts\Lexer\BufferInterface;
use Phplrt\Contracts\Lexer\LexerInterface;
use Phplrt\Contracts\Lexer\TokenInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Lexer\Lexer;
use Phplrt\Parser\Builder\BuilderInterface;
use Phplrt\Parser\Exception\ParserRuntimeException;
use Phplrt\Parser\Parser as BaseParser;
use Phplrt\Source\File;
use Railt\SDL\Exception\SyntaxErrorException;
use Railt\SDL\Frontend\Ast\Location;
use Railt\SDL\Frontend\Ast\Node;

/**
 * Class Parser
 */
final class Parser extends BaseParser implements BuilderInterface
{
    /**
     * @var array|\Closure[]
     */
    private array $reducers;

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        $grammar = require __DIR__ . '/grammar.php';

        $this->reducers = $grammar['reducers'];

        parent::__construct($this->bootLexer($grammar), $grammar['grammar'], [
            parent::CONFIG_AST_BUILDER  => $this,
            parent::CONFIG_INITIAL_RULE => $grammar['initial'],
        ]);
    }

    /**
     * @param array $grammar
     * @return LexerInterface
     */
    private function bootLexer(array $grammar): LexerInterface
    {
        return new Lexer($grammar['lexemes'], $grammar['skips']);
    }

    /**
     * {@inheritDoc}
     * @throws SyntaxErrorException
     * @throws \Throwable
     */
    public function build(
        ReadableInterface $file,
        RuleInterface $rule,
        TokenInterface $token,
        $state,
        $children
    ) {
        try {
            if (isset($this->reducers[$state])) {
                return $this->reducers[$state]($children);
            }

            return null;
        } catch (SyntaxErrorException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new SyntaxErrorException($e->getMessage(), $file, $token->getOffset());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function parse($source): iterable
    {
        $source = File::new($source);

        try {
            return parent::parse($source);
        } catch (ParserRuntimeException $e) {
            throw new SyntaxErrorException($e->getMessage(), $source, $e->getToken()->getOffset());
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function next(ReadableInterface $source, BufferInterface $buffer, $state)
    {
        $from = $buffer->current()->getOffset();

        $result = parent::next($source, $buffer, $state);

        if ($result instanceof Node) {
            $result->loc = new Location($source, $from, $buffer->current()->getOffset());
        }

        return $result;
    }
}
