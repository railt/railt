<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Spec;

/**
 * GraphQL Working Draft – October 2016
 *
 * @see https://graphql.github.io/graphql-spec/June2018/
 */
class October2016 extends Specification
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
    ];
}
