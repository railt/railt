<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation;

use Railt\Component\Http\Identifiable;
use Railt\Component\Http\RequestInterface;
use Railt\Component\Http\ResponseInterface;

/**
 * Interface ConnectionInterface
 */
interface ConnectionInterface extends Identifiable
{
    /**
     * @param RequestInterface|RequestInterface[]|iterable $requests
     * @return ResponseInterface
     */
    public function request($requests): ResponseInterface;

    /**
     * @return void
     */
    public function close(): void;
}
