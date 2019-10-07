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
 *
 * @method \Traversable|ExtensionInterface getIterator()
 */
interface RepositoryInterface extends \IteratorAggregate
{
    /**
     * @param string|ExtensionInterface $extension
     */
    public function add($extension): void;

    /**
     * @return void
     */
    public function boot(): void;
}
