<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer\Stream;

use Railt\Compiler\Lexer\Tokens\Output;

/**
 * Class TokenStream
 */
class TokenStream implements Stream
{
    /**
     * @var \Traversable
     */
    private $input;

    /**
     * @var \Closure[]
     */
    private $filters;

    /**
     * Stream constructor.
     * @param \Traversable $input
     */
    public function __construct(\Traversable $input)
    {
        $this->input = $input;
    }

    /**
     * @return \Traversable|array[]
     */
    public function getIterator(): \Traversable
    {
        yield from $this->get();
    }

    /**
     * @param string[] ...$names
     * @return Stream
     */
    public function channel(string ...$names): Stream
    {
        return $this->filter(function (array $token) use ($names): bool {
            return \in_array($token[Output::T_CHANNEL], $names, true);
        });
    }

    /**
     * @param \Closure $fn
     * @return Stream
     */
    public function filter(\Closure $fn): Stream
    {
        $this->filters[] = $fn;

        return $this;
    }

    /**
     * @param string[] ...$names
     * @return Stream
     */
    public function exceptChannel(string ...$names): Stream
    {
        return $this->filter(function (array $token) use ($names): bool {
            return ! \in_array($token[Output::T_CHANNEL], $names, true);
        });
    }

    /**
     * @param string[] ...$names
     * @return Stream
     */
    public function token(string ...$names): Stream
    {
        return $this->filter(function (array $token) use ($names): bool {
            return \in_array($token[Output::T_NAME], $names, true);
        });
    }

    /**
     * @param string[] ...$names
     * @return Stream
     */
    public function exceptToken(string ...$names): Stream
    {
        return $this->filter(function (array $token) use ($names): bool {
            return ! \in_array($token[Output::T_NAME], $names, true);
        });
    }

    /**
     * @return \Traversable|array[]
     */
    public function get(): \Traversable
    {
        foreach ($this->input as $token) {
            foreach ($this->filters as $filter) {
                if (! $filter($token)) {
                    continue 2;
                }
            }

            yield $token;
        }
    }
}
