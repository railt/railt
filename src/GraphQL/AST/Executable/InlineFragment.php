<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Executable;

use Railt\GraphQL\AST\Common\TypeCondition;

/**
 * Class InlineFragment
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
class InlineFragment extends AbstractSelectionSet
{
    /**
     * @var string
     */
    public $on;

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof TypeCondition) {
            $this->on = $value->type;

            return true;
        }

        return parent::each($value);
    }
}
