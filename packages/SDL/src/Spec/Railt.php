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
use Railt\SDL\Spec\Constraint\RepeatableDirectives;
use Railt\SDL\Spec\Constraint\TypeSystemExtensions;

/**
 * Railt GraphQL Specification
 *
 * @see https://railt.org/docs/language/syntax
 */
class Railt extends Specification
{
    /**
     * @var array|string[]
     */
    protected array $types = [
        'Boolean',
        'Float',
        'ID',
        'Int',
        'String',
        'Any',
        'DateTime',
        '@include',
        '@skip',
        '@deprecated',
    ];

    /**
     * @var array|Constraint[]
     */
    protected array $abilities = [
        RepeatableDirectives::class,
        TypeSystemExtensions::class,
    ];
}
