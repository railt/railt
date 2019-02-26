<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Proxy;

use Railt\GraphQL\AST\Common\Name;

/**
 * Trait NameProxyTrait
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
trait NameProxyTrait
{
    /**
     * @var string
     */
    public $name;

    /**
     * @param mixed $value
     * @return bool
     */
    protected function eachNameProxyTrait($value): bool
    {
        if ($value instanceof Name) {
            $this->name = $value->value;
            $this->offset = $value->offset;

            return true;
        }

        return false;
    }
}
