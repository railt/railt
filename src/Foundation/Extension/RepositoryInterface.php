<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Extension;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{
    /**
     * @param string $extension
     */
    public function add(string $extension): void;

    /**
     * @return iterable|ExtensionInterface[]
     */
    public function all(): iterable;

    /**
     * @return void
     */
    public function boot(): void;
}
