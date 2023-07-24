<?php

declare(strict_types=1);

namespace Railt\Http\Factory;

use Railt\Contracts\Http\Factory\RequestFactoryInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Http\GraphQLRequest;

final class GraphQLRequestFactory implements RequestFactoryInterface
{
    public function createRequest(string $query, array $variables = [], ?string $operationName = null): RequestInterface
    {
        return new GraphQLRequest(
            query: $query,
            variables: $this->filterVariables($variables),
            operationName: $operationName ?: null,
        );
    }

    /**
     * @return array<non-empty-string, mixed>
     * @psalm-suppress MixedAssignment
     */
    private function filterVariables(array $variables): array
    {
        $result = [];

        foreach ($variables as $key => $value) {
            if (\is_string($key) && $key !== '') {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
