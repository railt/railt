<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Runtime\Type;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;

/**
 * Interface DirectiveExecutionInterface
 */
interface DirectiveExecutionInterface extends ExecutionInterface
{
    /**
     * @return DirectiveInterface
     */
    public function getDirective(): DirectiveInterface;
}
