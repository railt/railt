<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Definitions;

use Railt\Compiler\Reflection\Base\Dependent\Argument\BaseArgumentsContainer;
use Railt\Compiler\Reflection\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Compiler\Reflection\Contracts\Definitions\InputDefinition;

/**
 * Class BaseInput
 */
abstract class BaseInput extends BaseDefinition implements InputDefinition
{
    use BaseArgumentsContainer;
    use BaseDirectivesContainer;

    /**
     * Input type name
     */
    protected const TYPE_NAME = 'Input';

    /**
     * TODO Verify input
     *
     * @param mixed $value
     * @return bool
     */
    public function isCompatible($value): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // trait HasArguments
            'arguments',

            // trait HasDirectives
            'directives',
        ]);
    }
}
