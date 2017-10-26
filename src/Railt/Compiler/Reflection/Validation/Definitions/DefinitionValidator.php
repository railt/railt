<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Definitions;

use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Validation\Validator;

/**
 * Interface DefinitionValidator
 */
interface DefinitionValidator
{
    /**
     * @param Definition $definition
     * @return bool
     */
    public function match(Definition $definition): bool;

    /**
     * DefinitionValidator constructor.
     * @param Validator $validator
     */
    public function __construct(Validator $validator);

    /**
     * @param Definition $definition
     * @return void
     */
    public function verify(Definition $definition): void;
}
