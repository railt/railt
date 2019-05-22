<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Executable;

use Railt\GraphQL\AST\Proxy\NameProxyTrait;

/**
 * Class Field
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
class Field extends AbstractSelectionSet
{
    use NameProxyTrait;

    /**
     * @var string|null
     */
    public $alias;

    /**
     * @var array|Argument[]
     */
    public $arguments = [];

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof Alias) {
            $this->alias = $value->name;

            return true;
        }

        if ($value instanceof Argument) {
            $this->arguments[] = $value;

            return true;
        }

        return parent::each($value);
    }
}
