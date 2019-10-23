<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory\Resolver;

use Railt\Http\Request;
use Railt\Contracts\Http\RequestInterface;
use Railt\Contracts\HttpFactory\Provider\ProviderInterface;
use Railt\Contracts\HttpFactory\Resolver\ResolverInterface;

/**
 * Class Resolver
 */
abstract class Resolver implements ResolverInterface
{
    /**
     * @param ProviderInterface $provider
     * @return RequestInterface|null
     */
    public function resolve(ProviderInterface $provider): ?RequestInterface
    {
        if (! $this->match($provider)) {
            return null;
        }

        $body = $this->read($provider);

        return new Request(
            $this->readQuery($body),
            $this->readVariables($body),
            $this->readOperationName($body),
        );
    }

    /**
     * @param ProviderInterface $provider
     * @return bool
     */
    abstract protected function match(ProviderInterface $provider): bool;

    /**
     * @param ProviderInterface $provider
     * @return array
     */
    abstract protected function read(ProviderInterface $provider): array;

    /**
     * @param array $data
     * @return string
     */
    protected function readQuery(array $data): string
    {
        $query = $data[Request::FIELD_QUERY] ?? '';

        return \is_scalar($query) ? (string)$query : '';
    }

    /**
     * @param array $data
     * @return array
     */
    protected function readVariables(array $data): array
    {
        return (array)($data[Request::FIELD_VARIABLES] ?? []);
    }

    /**
     * @param array $data
     * @return string|null
     */
    protected function readOperationName(array $data): ?string
    {
        $operation = $data[Request::FIELD_OPERATION_NAME] ?? '';

        return \is_scalar($operation) ? (string)$operation : null;
    }
}
