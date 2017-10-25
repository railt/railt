<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation;

use Railt\Compiler\Exceptions\TypeRedefinitionException;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;

/**
 * Trait DefinitionValidator
 */
trait DefinitionValidator
{
    /**
     * @param array $definable
     * @param Definition $new
     * @return array
     * @throws \Railt\Compiler\Exceptions\TypeRedefinitionException
     */
    protected function verifyDefinition(array $definable, Definition $new): array
    {
        if (\array_key_exists($new->getName(), $definable)) {
            $error = \sprintf('Can not redefined already defined %s %s', $new->getTypeName(), $new->getName());
            throw new TypeRedefinitionException($error);
        }

        $definable[$new->getName()] = $new;

        return $definable;
    }
}
