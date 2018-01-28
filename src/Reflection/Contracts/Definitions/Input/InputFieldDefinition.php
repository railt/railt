<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Contracts\Definitions\Input;

use Railt\Reflection\Contracts\Definitions\Common\Dependent;
use Railt\Reflection\Contracts\Definitions\Common\HasDefaultValue;
use Railt\Reflection\Contracts\Definitions\Common\HasTypeIndication;
use Railt\Reflection\Contracts\Definitions\InputDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Invocations\Directive\HasDirectives;

/**
 * Interface InputFieldDefinition
 */
interface InputFieldDefinition extends Dependent, TypeDefinition, HasDefaultValue, HasTypeIndication, HasDirectives
{
    /**
     * @return InputDefinition
     */
    public function getParent(): InputDefinition;
}
