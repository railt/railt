<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Base\Invocations;

use Railt\SDL\Base\Dependent\BaseDependent;
use Railt\SDL\Base\Invocations\Argument\HasPassedArguments;
use Railt\SDL\Contracts\Invocations\DirectiveInvocation;
use Railt\SDL\Contracts\Type;

/**
 * Class BaseDirectiveInvocation
 */
abstract class BaseDirectiveInvocation extends BaseDependent implements DirectiveInvocation
{
    use HasPassedArguments;

    /**
     * Directive type name
     */
    protected const TYPE_NAME = Type::DIRECTIVE_INVOCATION;
}
