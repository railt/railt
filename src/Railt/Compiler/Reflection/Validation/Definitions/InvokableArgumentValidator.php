<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Definitions;

use Railt\Compiler\Exceptions\TypeConflictException;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Invocations\ArgumentInvocation;

/**
 * Class InvokableArgumentValidator
 */
class InvokableArgumentValidator extends BaseDefinitionValidator
{
    /**
     * @param Definition $definition
     * @return bool
     */
    public function match(Definition $definition): bool
    {
        return $definition instanceof ArgumentInvocation;
    }

    /**
     * @param Definition|ArgumentInvocation $type
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    public function validate(Definition $type): void
    {
        $this->getCallStack()->push($type);

        $this->validateSuperfluousArgument($type);

        $this->getCallStack()->pop();
    }

    /**
     * @param ArgumentInvocation $argument
     * @return void
     */
    private function validateSuperfluousArgument(ArgumentInvocation $argument): void
    {
        $definition = $argument->getTypeDefinition();

        if ($definition === null) {
            $error = \vsprintf('In the %s there is no specified %s', [
                $argument->getParent(),
                $argument,
            ]);

            throw new TypeConflictException($error, $this->getCallStack());
        }
    }
}
