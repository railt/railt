<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Compiler\Grammar\Builder;

use Railt\Component\Parser\Rule\Repetition as RepetitionRule;
use Railt\Component\Parser\Rule\Rule;

/**
 * Class Repetition
 */
class Repetition extends AbstractBuilder
{
    /**
     * @var int
     */
    private $min;

    /**
     * @var int
     */
    private $max;

    /**
     * Repetition constructor.
     * @param $name
     * @param int $min
     * @param int $max
     * @param $children
     * @param string|null $nodeId
     */
    public function __construct($name, int $min, int $max, $children, string $nodeId = null)
    {
        $this->min = $min;
        $this->max = $max;
        parent::__construct($name, $children, $nodeId);
    }

    /**
     * @return Rule|RepetitionRule
     */
    public function build(): Rule
    {
        $rule = new RepetitionRule($this->name, $this->min, $this->max, $this->children, $this->nodeId);
        $rule->setDefaultId($this->defaultId);

        return $rule;
    }
}
