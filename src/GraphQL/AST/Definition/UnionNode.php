<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Definition;

use Railt\GraphQL\AST\Node;
use Railt\GraphQL\AST\Proxy\DescriptionProxyTrait;
use Railt\GraphQL\AST\Proxy\DirectiveProxyTrait;
use Railt\GraphQL\AST\Proxy\NameProxyTrait;
use Railt\Parser\Ast\RuleInterface;

/**
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
class UnionNode extends Node
{
    use NameProxyTrait;
    use DirectiveProxyTrait;
    use DescriptionProxyTrait;

    /**
     * @var array|string[]
     */
    public $types = [];

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($this->isUnionTargets($value)) {
            foreach ($value->getChildren() as $target) {
                $this->types[] = $target->value;
            }

            return true;
        }

        return parent::each($value);
    }

    /**
     * @param RuleInterface|mixed $ast
     * @return bool
     */
    private function isUnionTargets($ast): bool
    {
        return $ast instanceof RuleInterface && $ast->is('UnionDefinitionTargets');
    }
}
