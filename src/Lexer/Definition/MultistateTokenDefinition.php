<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer\Definition;

/**
 * Class MultistateTokenDefinition
 */
class MultistateTokenDefinition extends TokenDefinition
{
    /**
     * @var int
     */
    private $state;

    /**
     * @var int
     */
    private $nextState;

    /**
     * MultistateTokenDefinition constructor.
     * @param string $name
     * @param string $pcre
     * @param bool $keep
     * @param int $state
     * @param int $nextState
     */
    public function __construct(string $name, string $pcre, bool $keep = true, int $state = 0, int $nextState = 0)
    {
        parent::__construct($name, $pcre, $keep);
        $this->state     = $state;
        $this->nextState = $nextState;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @return int
     */
    public function getNextState(): int
    {
        return $this->nextState;
    }
}
