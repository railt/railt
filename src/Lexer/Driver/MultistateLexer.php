<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer\Driver;

use Railt\Lexer\Definition\MultistateTokenDefinition;
use Railt\Lexer\MultistateLexerInterface;

/**
 * Class MultistateLexer
 */
abstract class MultistateLexer extends SimpleLexer implements MultistateLexerInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_STATE = 0x00;

    /**
     * @var array|int[]
     */
    protected $states = [];

    /**
     * @var array|int[]
     */
    protected $transitions = [];

    /**
     * @param string $token
     * @param int $state
     * @param int|null $nextState
     * @return MultistateLexerInterface
     */
    public function state(string $token, int $state, int $nextState = null): MultistateLexerInterface
    {
        $this->states[$token] = $state;

        if ($nextState !== null) {
            $this->transitions[$token] = $nextState;
        }

        return $this;
    }

    /**
     * @return iterable
     */
    public function getTokenDefinitions(): iterable
    {
        foreach ($this->tokens as $name => $pcre) {
            $keep = ! \in_array($name, $this->skipped, true);
            $state = $this->getTokenState($name);
            $next = $this->getNextState($name);

            yield new MultistateTokenDefinition($name, $pcre, $keep, $state, $next);
        }
    }

    /**
     * @param string $token
     * @return int
     */
    protected function getTokenState(string $token): int
    {
        return $this->states[$token] ?? static::DEFAULT_STATE;
    }

    /**
     * @param string $token
     * @return int
     */
    protected function getNextState(string $token): int
    {
        return $this->transitions[$this->getTokenState($token)] ?? static::DEFAULT_STATE;
    }
}
