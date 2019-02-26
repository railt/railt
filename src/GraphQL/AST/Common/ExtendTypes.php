<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Common;

use Railt\GraphQL\AST\Node;
use Railt\GraphQL\AST\Proxy\NameProxyTrait;

/**
 * Class ExtendTypes
 */
class ExtendTypes extends Node
{
    use NameProxyTrait;
}
