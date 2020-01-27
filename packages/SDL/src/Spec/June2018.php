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
use Railt\SDL\Spec\Constraint\TypeSystemExtensions;

/**
 * GraphQL June 2018 Edition
 *
 * @see https://graphql.github.io/graphql-spec/June2018/
 */
class June2018 extends Specification
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
        '@include',
        '@skip',
        '@deprecated',
    ];

    /**
     * @var array|Constraint[]
     */
    protected array $abilities = [
        TypeSystemExtensions::class,
    ];
}
