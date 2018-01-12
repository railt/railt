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
use Railt\Reflection\Contracts\Document;

/**
 * Class Proxy
 */
class Proxy implements Persister
{
    /**
     * @var Persister
     */
    private $front;

    /**
     * @var Persister
     */
    private $fallback;

    /**
     * Proxy constructor.
     * @param Persister $front
     * @param Persister $fallback
     */
    public function __construct(Persister $front, Persister $fallback)
    {
        $this->front    = $front;
        $this->fallback = $fallback;
    }

    /**
     * @param Readable $readable
     * @param \Closure $then
     * @return Document
     */
    public function remember(Readable $readable, \Closure $then): Document
    {
        return $this->front->remember($readable, function (Readable $readable) use ($then): Document {
            return $this->fallback->remember($readable, $then);
        });
    }
}
