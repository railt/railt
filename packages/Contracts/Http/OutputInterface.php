<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Contracts\Http;

use Railt\Contracts\Http\Output\ProvidesDataInterface;

/**
 * Interface OutputInterface
 */
interface OutputInterface extends ProvidesDataInterface
{
    /**
     * @return mixed
     */
    public function result();

    /**
     * @return string
     */
    public function type(): string;

    /**
     * @return iterable|\Throwable[]
     */
    public function exceptions(): iterable;
}
