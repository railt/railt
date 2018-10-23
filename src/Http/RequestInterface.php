<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Provider\ProviderInterface;

/**
 * Interface RequestInterface
 */
interface RequestInterface extends ProviderInterface, QueryInterface, \IteratorAggregate
{
    /**
     * @param ProviderInterface $provider
     * @return RequestInterface
     */
    public function addProvider(ProviderInterface $provider): self;

    /**
     * @param QueryInterface $query
     * @return RequestInterface
     */
    public function addQuery(QueryInterface $query): self;

    /**
     * @return QueryInterface
     */
    public function first(): QueryInterface;

    /**
     * @return bool
     */
    public function isBatched(): bool;
}
