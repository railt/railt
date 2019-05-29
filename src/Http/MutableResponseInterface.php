<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Extension\MutableExtensionProviderInterface;
use Railt\Http\Response\MutableExceptionsProviderInterface;

/**
 * Interface MutableResponseInterface
 */
interface MutableResponseInterface extends
    MutableExtensionProviderInterface,
    MutableExceptionsProviderInterface,
    ResponseInterface
{
    /**
     * @param array|null $data
     * @return MutableResponseInterface|$this
     */
    public function withData(?array $data): self;
}
