<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Spec;

use Railt\SDL\Spec\Constraint\Constraint;

/**
 * @internal This specification is used for testing
 */
class RawSpecification extends Specification
{
    /**
     * Remove all default language constraints
     *
     * @var array|Constraint[]
     */
    protected const STANDARD_CONSTRAINTS = [];
}
