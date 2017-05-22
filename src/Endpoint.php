<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun;

use Serafim\Railgun\Adapters\Webonyx\Endpoint as Webonyx;
use Serafim\Railgun\Contracts\Adapters\EndpointDriverInterface;
use Serafim\Railgun\Contracts\Adapters\EndpointInterface;
use Serafim\Railgun\Contracts\Partials\MutationTypeInterface;
use Serafim\Railgun\Contracts\Partials\QueryTypeInterface;
use Serafim\Railgun\Contracts\TypesRegistryInterface;
use Serafim\Railgun\Requests\RequestInterface;
use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Support\InteractWithTypesRegistry;
use Serafim\Railgun\Types\Creators\Fields;
use Serafim\Railgun\Types\TypesRegistry;

/**
 * Class Schema
 * @package Serafim\Railgun
 */
class Endpoint implements EndpointInterface
{
    use InteractWithName;
    use InteractWithTypesRegistry;

    /**
     * @var array|EndpointDriverInterface[]
     */
    private $drivers = [
        Webonyx::class,
    ];

    /**
     * @var EndpointDriverInterface
     */
    private $endpoint;

    /**
     * Endpoint constructor.
     * @param string $name
     * @param null|TypesRegistryInterface $registry
     * @throws \DomainException
     * @throws \InvalidArgumentException
     */
    public function __construct(string $name = 'root', ?TypesRegistryInterface $registry = null)
    {
        $this->name = $name;
        $this->registry = $registry ?? new TypesRegistry();

        $this->endpoint = $this->resolveDriver();
    }

    /**
     * @return EndpointDriverInterface
     * @throws \DomainException
     */
    private function resolveDriver(): EndpointDriverInterface
    {
        foreach ($this->drivers as $driver) {
            if ($driver::isSupportedBy()) {
                return new $driver($this->name, $this->registry);
            }
        }

        $message = 'Can not resolve any supported GraphQL driver in [%s]';
        throw new \DomainException(sprintf($message, implode($this->drivers, ', ')));
    }

    /**
     * @param string $name
     * @param QueryTypeInterface $query
     * @return EndpointInterface
     */
    public function query(string $name, QueryTypeInterface $query): EndpointInterface
    {
        return $this->endpoint->query($name, $query);
    }

    /**
     * @param string $name
     * @param MutationTypeInterface $mutation
     * @return EndpointInterface
     */
    public function mutation(string $name, MutationTypeInterface $mutation): EndpointInterface
    {
        return $this->endpoint->mutation($name, $mutation);
    }

    /**
     * @param RequestInterface $request
     * @param null $context
     * @return array
     */
    public function request(RequestInterface $request, $context = null): array
    {
        return $this->endpoint->request($request, $context);
    }
}
