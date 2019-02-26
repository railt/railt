<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Proxy;

use Railt\GraphQL\AST\Common\Description;

/**
 * Trait DescriptionProxyTrait
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
trait DescriptionProxyTrait
{
    /**
     * @var string|null
     */
    public $description;

    /**
     * @param mixed $value
     * @return bool
     */
    protected function eachDescriptionProxyTrait($value): bool
    {
        if ($value instanceof Description) {
            $this->description = $value->value;

            return true;
        }

        return false;
    }
}
