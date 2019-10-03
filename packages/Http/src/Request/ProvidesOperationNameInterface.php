<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

/**
 * Interface OperationNameInterface
 */
interface ProvidesOperationNameInterface
{
    /**
     * @return string|null
     */
    public function getOperationName(): ?string;

    /**
     * @return bool
     */
    public function hasOperationName(): bool;
}
