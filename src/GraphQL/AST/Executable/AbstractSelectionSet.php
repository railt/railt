<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Executable;

use Railt\GraphQL\AST\Node;
use Railt\GraphQL\AST\Proxy\DirectiveProxyTrait;

/**
 * Class AbstractSelectionSet
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
abstract class AbstractSelectionSet extends Node
{
    use DirectiveProxyTrait;

    /**
     * @var string[]
     */
    private const SUB_SELECTIONS = [
        InlineFragment::class,
        FragmentSpread::class,
        Field::class,
    ];

    /**
     * @var array|InlineFragment[]|FragmentSpread[]|Field[]
     */
    public $selections = [];

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if (\in_array(\get_class($value), self::SUB_SELECTIONS, true)) {
            $this->selections[] = $value;

            return true;
        }

        return parent::each($value);
    }
}
