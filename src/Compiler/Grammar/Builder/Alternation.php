<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Builder;

use Railt\Parser\Rule\Alternation as AlternationRule;
use Railt\Parser\Rule\Rule;

/**
 * Class Alternation
 */
class Alternation extends AbstractBuilder
{
    /**
     * @return Rule|AlternationRule
     */
    public function build(): Rule
    {
        $rule = new AlternationRule($this->name, $this->children, $this->nodeId);
        $rule->setDefaultId($this->defaultId);

        return $rule;
    }
}
