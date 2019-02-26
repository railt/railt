<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\TypeHint;

use Railt\GraphQL\AST\Node;
use Railt\GraphQL\AST\Proxy\TypeHintProxyTrait;

/**
 * Class NonNullType
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
final class NonNullType extends Node implements TypeHintInterface
{
    use TypeHintProxyTrait;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->of . '!';
    }
}
