<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Runtime;

use GraphQL\Contracts\TypeSystem\DirectiveInterface;

/**
 * Interface DirectiveExecutionInterface
 */
interface DirectiveExecutionInterface extends ExecutionInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}
