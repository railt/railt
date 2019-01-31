<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Http\Identifiable;
use Railt\Http\Provider\ProviderInterface;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;

/**
 * Interface ConnectionInterface
 */
interface ConnectionInterface extends Identifiable
{
    /**
     * @param RequestInterface|RequestInterface[] $requests
     * @return ResponseInterface
     */
    public function request($requests): ResponseInterface;

    /**
     * @return void
     */
    public function close(): void;
}
