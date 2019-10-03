<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response\Extension;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{
    /**
     * @param string $name
     * @param mixed $data
     * @return RepositoryInterface|$this
     */
    public function add(string $name, $data): self;

    /**
     * @return array
     */
    public function toArray(): array;
}
