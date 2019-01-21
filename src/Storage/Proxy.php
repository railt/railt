<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Storage;

use Railt\Io\Readable;

/**
 * Class Proxy
 */
class Proxy implements Storage
{
    /**
     * @var Storage
     */
    private $front;

    /**
     * @var Storage
     */
    private $fallback;

    /**
     * Proxy constructor.
     *
     * @param Storage $front
     * @param Storage $fallback
     */
    public function __construct(Storage $front, Storage $fallback)
    {
        $this->front = $front;
        $this->fallback = $fallback;
    }

    /**
     * @param Readable $readable
     * @param \Closure $then
     * @return object|mixed
     */
    public function remember(Readable $readable, \Closure $then)
    {
        return $this->front->remember($readable, function (Readable $readable) use ($then) {
            return $this->fallback->remember($readable, $then);
        });
    }
}
