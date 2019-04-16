<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Coercion;

use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;
use Railt\Component\SDL\Contracts\Invocations\DirectiveInvocation;
use Railt\Component\SDL\Standard\Directives\Deprecation;

/**
 * Class DeprecationCoercion
 */
class DeprecationCoercion extends BaseTypeCoercion
{
    /**
     * @param TypeDefinition $type
     * @return bool
     */
    public function match(TypeDefinition $type): bool
    {
        return $type instanceof DirectiveInvocation &&
            $type->getName() === Deprecation::DIRECTIVE_TYPE_NAME;
    }

    /**
     * @param TypeDefinition|DirectiveInvocation $directive
     */
    public function apply(TypeDefinition $directive): void
    {
        $applier = function () use ($directive): void {
            $this->deprecationReason = $directive->getPassedArgument(Deprecation::REASON_ARGUMENT);
        };

        $applier->call($directive->getParent());
    }
}
