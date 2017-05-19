<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Contracts\Adapters;

use Serafim\Railgun\Contracts\ContainsNameInterface;
use Serafim\Railgun\Contracts\Partials\FieldTypeInterface;
use Serafim\Railgun\Contracts\Partials\MutationTypeInterface;
use Serafim\Railgun\Contracts\Partials\QueryTypeInterface;
use Serafim\Railgun\Contracts\ProvidesTypeRegistryInterface;

/**
 * Interface ResponderInterface
 * @package Serafim\Railgun\Contracts\Adapters
 */
interface EndpointInterface extends
    ResponderInterface,
    ContainsNameInterface,
    ProvidesTypeRegistryInterface
{
    /**
     * @param string $name
     * @param QueryTypeInterface $query
     * @return EndpointInterface
     */
    public function query(string $name, QueryTypeInterface $query): EndpointInterface;

    /**
     * @param string $name
     * @param MutationTypeInterface $mutation
     * @return EndpointInterface
     */
    public function mutation(string $name, MutationTypeInterface $mutation): EndpointInterface;
}
