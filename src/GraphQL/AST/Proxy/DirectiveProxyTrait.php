<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Proxy;

use Railt\GraphQL\AST\Executable\Directive;

/**
 * Trait DirectiveProxyTrait
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
trait DirectiveProxyTrait
{
    /**
     * @var array|Directive[]
     */
    public $directives = [];

    /**
     * @param mixed $value
     * @return bool
     */
    protected function eachDirectiveProxyTrait($value): bool
    {
        if ($value instanceof Directive) {
            $this->directives[] = $value;

            return true;
        }

        return false;
    }
}
