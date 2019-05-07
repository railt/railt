<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base\Definitions;

use Railt\SDL\Base\Dependent\Argument\BaseArgumentsContainer;
use Railt\SDL\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\SDL\Contracts\Definitions\InputDefinition;
use Railt\SDL\Contracts\Invocations\InputInvocation;
use Railt\SDL\Contracts\Type;

/**
 * Class BaseInput
 */
abstract class BaseInput extends BaseTypeDefinition implements InputDefinition
{
    use BaseArgumentsContainer;
    use BaseDirectivesContainer;

    /**
     * Input type name
     */
    protected const TYPE_NAME = Type::INPUT;

    /**
     * @param mixed $value
     * @return bool
     */
    public function isCompatible($value): bool
    {
        return $value instanceof InputInvocation;
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
