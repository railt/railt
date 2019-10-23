<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Console;

use Symfony\Component\Console\Command\Command;

/**
 * Interface RepositoryInterface
 * @method iterable|Command[] getIterator()
 */
interface RepositoryInterface extends \IteratorAggregate
{
    /**
     * @param Command|string $command
     * @return void
     */
    public function add($command): void;
}
