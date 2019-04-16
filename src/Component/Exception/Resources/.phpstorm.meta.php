<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace PHPSTORM_META {

    registerArgumentsSet('phplrt_trace',
        'class',
        'type',
        'function',
        'args',
        'file',
        'line',
        'column'
    );

    registerArgumentsSet('phplrt_trace_items',
        \Railt\Component\Exception\Trace\Item::class,
        \Railt\Component\Exception\Trace\FunctionItem::class,
        \Railt\Component\Exception\Trace\ObjectItem::class
    );

    expectedArguments(\Railt\Component\Exception\Trace\Item::fromArray(), 0, argumentsSet('phplrt_trace'));
    expectedArguments(\Railt\Component\Exception\Trace\FunctionItem::fromArray(), 0, argumentsSet('phplrt_trace'));
    expectedArguments(\Railt\Component\Exception\Trace\ObjectItem::fromArray(), 0, argumentsSet('phplrt_trace'));

    expectedArguments(\Railt\Component\Exception\MutableTraceInterface::withTrace(), 0, argumentsSet('phplrt_trace_items'));
}
