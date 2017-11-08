<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Definitions;

use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Definitions\ObjectDefinition;
use Railt\Compiler\Reflection\Validation\Inheritance;

/**
 * Class ObjectValidator
 */
class ObjectValidator extends BaseDefinitionValidator
{
    /**
     * @param Definition $definition
     * @return bool
     */
    public function match(Definition $definition): bool
    {
        return $definition instanceof ObjectDefinition;
    }

    /**
     * @param Definition|ObjectDefinition $object
     * @return void
     * @throws \OutOfBoundsException
     */
    public function validate(Definition $object): void
    {
        $validator = $this->getValidator(Inheritance::class);

        foreach ($object->getInterfaces() as $interface) {
            $validator->validate($interface, $object);
        }
    }
}
