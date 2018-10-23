<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider;

use Railt\Http\Provider\Support\JsonRequestDetector;
use Railt\Http\Query;
use Railt\Http\QueryInterface;

/**
 * Class Provider
 */
abstract class Provider implements ProviderInterface
{
    use JsonRequestDetector;

    /**
     * @var string Content-Type header key name from $_SERVER variable
     */
    protected const CONTENT_TYPE_KEY = 'CONTENT_TYPE';

    /**
     * @var string Default Content-Type value
     */
    protected const CONTENT_TYPE_DEFAULT = 'application/octet-stream';

    /**
     * @return iterable|QueryInterface[]
     */
    public function getQueries(): iterable
    {
        foreach ($this->getArguments() as $arguments) {
            [$query, $vars, $operation] = $this->parseArguments($arguments);

            if (! \is_string($query)) {
                continue;
            }

            yield new Query($query, $vars, $operation);
        }
    }

    /**
     * @return array
     */
    private function getArguments(): iterable
    {
        $arguments = $this->isJson() ? $this->getJson() : $this->getRequestArguments();

        if ($this->isApolloBatching($arguments)) {
            yield $arguments;
        } elseif ($this->isHttpBatching($arguments)) {
            yield from $this->extractHttpBatching($arguments);
        } else {
            yield $arguments;
        }
    }

    /**
     * @return bool
     */
    abstract protected function isJson(): bool;

    /**
     * @return array
     */
    abstract protected function getJson(): array;

    /**
     * @return iterable|string[]
     */
    abstract protected function getRequestArguments(): iterable;

    /**
     * Apollo provides request body in the given format:
     * <code>
     *      POST / HTTP/2.0
     *      Host: site.com
     *      Content-Type: application/json
     *
     *      [{"query": <GRAPHQL_QUERY>}, {"query": <GRAPHQL_QUERY>, "variables": <GRAPHQL_VARIABLES>}]
     * </code>
     * After parsing, we get the following array, in which
     * there is no "query" field.
     *
     * @see https://dev-blog.apollodata.com/query-batching-in-apollo-63acfd859862
     * @param mixed $arguments
     * @return bool
     */
    private function isApolloBatching($arguments): bool
    {
        return \is_array($arguments) &&
            \array_key_exists(0, $arguments) &&
            \array_key_exists(QueryInterface::QUERY_ARGUMENT, $arguments[0]);
    }

    /**
     * As an alternative, the client can use the standard HTTP format, like:
     * <code>
     *      POST / HTTP/2.0
     *      Host: site.com
     *      Content-Type: application/x-www-form-urlencoded
     *
     *      query[a]=<GRAPHQL_QUERY>&query[b]=<GRAPHQL_QUERY>&variables[b]=<GRAPHQL_VARIABLES>
     * </code>
     *
     * @param $arguments
     * @return bool
     */
    private function isHttpBatching($arguments): bool
    {
        return \is_array($arguments) &&
            \array_key_exists(QueryInterface::QUERY_ARGUMENT, $arguments) &&
            \is_array($arguments[QueryInterface::QUERY_ARGUMENT]);
    }

    /**
     * @param array $arguments
     * @return iterable
     */
    private function extractHttpBatching(array $arguments): iterable
    {
        foreach ((array)$arguments[QueryInterface::QUERY_ARGUMENT] as $index => $argument) {
            $variables = $arguments[QueryInterface::VARIABLES_ARGUMENT][$index] ?? [];
            $operation = $arguments[QueryInterface::OPERATION_ARGUMENT][$index] ?? null;

            yield [
                QueryInterface::QUERY_ARGUMENT     => $argument,
                QueryInterface::VARIABLES_ARGUMENT => $variables,
                QueryInterface::OPERATION_ARGUMENT => $operation,
            ];
        }
    }

    /**
     * @param array $arguments
     * @return array
     */
    private function parseArguments(array $arguments): array
    {
        return [
            $arguments[QueryInterface::QUERY_ARGUMENT] ?? null,
            $this->parseVariables($arguments[QueryInterface::VARIABLES_ARGUMENT] ?? null),
            $arguments[QueryInterface::OPERATION_ARGUMENT] ?? null,
        ];
    }

    /**
     * @param string|array|null|mixed $variables
     * @return array
     */
    protected function parseVariables($variables): array
    {
        if (\is_array($variables)) {
            return $variables;
        }

        if (\is_string($variables)) {
            try {
                return $this->parseJson($variables, false);
            } catch (\Throwable $e) {
                return [];
            }
        }

        return [];
    }
}
