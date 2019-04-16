<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Base\Dependent;

use Railt\Component\SDL\Base\Behavior\BaseTypeIndicator;
use Railt\Component\SDL\Base\Dependent\Argument\BaseArgumentsContainer;
use Railt\Component\SDL\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Component\SDL\Contracts\Dependent\FieldDefinition;
use Railt\Component\SDL\Contracts\Type;

/**
 * Class BaseField
 */
abstract class BaseField extends BaseDependent implements FieldDefinition
{
    use BaseTypeIndicator;
    use BaseArgumentsContainer;
    use BaseDirectivesContainer;

    /**
     * Field type name
     *
     * TODO or interface field
     */
    protected const TYPE_NAME = Type::OBJECT_FIELD;

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

            // trait AllowsTypeIndication
            'type',
            'isList',
            'isNonNull',
            'isListOfNonNulls',
        ]);
    }
}
