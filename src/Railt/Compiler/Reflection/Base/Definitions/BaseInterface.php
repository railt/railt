<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Definitions;

use Railt\Compiler\Reflection\Base\Dependent\Field\BaseFieldsContainer;
use Railt\Compiler\Reflection\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Compiler\Reflection\Contracts\Definitions\InterfaceDefinition;

/**
 * Class BaseInterface
 */
abstract class BaseInterface extends BaseTypeDefinition implements InterfaceDefinition
{
    use BaseFieldsContainer;
    use BaseDirectivesContainer;

    /**
     * Base type name
     */
    protected const TYPE_NAME = 'Interface';

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // trait HasFields
            'fields',

            // trait HasDirectives
            'directives',
        ]);
    }
}
