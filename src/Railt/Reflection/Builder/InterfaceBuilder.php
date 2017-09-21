<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder;

use Railt\Reflection\Builder\Support\Directives;
use Railt\Reflection\Builder\Support\Fields;
use Railt\Reflection\Contracts\Types\InterfaceType;

/**
 * Class InterfaceBuilder
 */
class InterfaceBuilder extends AbstractNamedTypeBuilder implements InterfaceType
{
    use Fields;
    use Directives;
}
