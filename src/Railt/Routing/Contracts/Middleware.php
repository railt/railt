<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Contracts;

/**
 * Interface Middleware
 */
interface Middleware
{
    /**
     * @param InputInterface $input
     * @param \Closure $then
     * @param array ...$args
     * @return OutputInterface
     */
    public function handle(InputInterface $input, \Closure $then, ...$args): OutputInterface;
}
