<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Builder;

use Railt\Parser\Rule\Rule;
use Railt\Parser\Rule\Terminal as TerminalRule;

/**
 * Class Terminal
 */
class Terminal extends AbstractBuilder
{
    /**
     * @var bool
     */
    private $kept;

    /**
     * Terminal constructor.
     * @param $name
     * @param string $tokenName
     * @param bool $kept
     */
    public function __construct($name, string $tokenName, bool $kept = false)
    {
        $this->kept = $kept;
        parent::__construct($name, null, $tokenName);
    }

    /**
     * @return Rule
     */
    public function build(): Rule
    {
        return new TerminalRule($this->name, $this->nodeId, $this->kept);
    }
}
