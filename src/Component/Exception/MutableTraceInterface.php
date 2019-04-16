<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Exception;

use Railt\Component\Exception\Trace\FunctionItemInterface;
use Railt\Component\Exception\Trace\ItemInterface;
use Railt\Component\Exception\Trace\ObjectItemInterface;

/**
 * Interface MutableTraceInterface
 */
interface MutableTraceInterface
{
    /**
     * @param ItemInterface|FunctionItemInterface|ObjectItemInterface $item
     * @return ItemInterface|FunctionItemInterface|ObjectItemInterface
     */
    public function withTrace(ItemInterface $item): ItemInterface;
}
