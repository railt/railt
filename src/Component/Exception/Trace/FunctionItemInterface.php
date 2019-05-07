<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Exception\Trace;

/**
 * Interface FunctionItemInterface
 */
interface FunctionItemInterface extends ItemInterface
{
    /**
     * @return string
     */
    public function getFunction(): string;

    /**
     * @return array
     */
    public function getArguments(): array;
}
