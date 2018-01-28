<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable\Extractors;

use Railt\Compiler\Ast\RuleInterface;

/**
 * Class BaseExtractor
 */
abstract class BaseExtractor implements Extractor
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool
    {
        return \in_array($rule->getName(), $this->getAstNodeNames(), true);
    }

    /**
     * @return array
     */
    abstract protected function getAstNodeNames(): array;
}
