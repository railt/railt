<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Storage\Drivers;

use Railt\Io\Readable;
use Railt\Storage\Storage;

/**
 * Class NullableStorage
 */
class NullableStorage implements Storage
{
    /**
     * @param Readable $readable
     * @param \Closure $then
     * @return object|mixed
     */
    public function remember(Readable $readable, \Closure $then)
    {
        return $then($readable);
    }
}
