<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Http;

use Railt\Http\ResponseInterface;
use Railt\Foundation\Event\Connection\ProvidesConnection;

/**
 * Interface ResponseEventInterface
 */
interface HttpEventInterface extends ProvidesConnection, ProvidesRequest
{
    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface;
}
