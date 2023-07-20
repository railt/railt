<?php

declare(strict_types=1);

namespace Railt\Http\Factory;

use Railt\Contracts\Http\Factory\AdapterInterface;
use Railt\Contracts\Http\Factory\RequestFactoryInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Http\Factory\Exception\ParsingException;
use Railt\Http\Request;

final class RequestFactory implements RequestFactoryInterface
{
    /**
     * Query http (GET/POST) field name passed by default.
     *
     * @var non-empty-string
     */
    public const FIELD_QUERY = 'query';

    /**
     * Variables http (GET/POST) field name passed by default.
     *
     * @var non-empty-string
     */
    public const FIELD_VARIABLES = 'variables';

    /**
     * Operation http (GET/POST) field name passed by default.
     *
     * @var non-empty-string
     */
    public const FIELD_OPERATION_NAME = 'operationName';

    public function createRequest(string $query, array $variables = [], ?string $operationName = null): RequestInterface
    {
        return new Request(
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

    public function createEmptyRequest(): RequestInterface
    {
        return $this->createRequest('');
    }

    public function createRequestFromArray(array $data): RequestInterface
    {
        $data = [
            self::FIELD_QUERY => '',
            self::FIELD_VARIABLES => [],
            self::FIELD_OPERATION_NAME => null,
            ...$data,
        ];

        if (!\is_string($data[self::FIELD_QUERY])) {
            throw ParsingException::fromInvalidQueryField($data[self::FIELD_QUERY]);
        }

        if (!\is_array($data[self::FIELD_VARIABLES])) {
            throw ParsingException::fromInvalidVariablesField($data[self::FIELD_VARIABLES]);
        }

        if (isset($data[self::FIELD_OPERATION_NAME]) && !\is_string($data[self::FIELD_OPERATION_NAME])) {
            throw ParsingException::fromInvalidVariablesField($data[self::FIELD_OPERATION_NAME]);
        }

        return $this->createRequest(
            $data[self::FIELD_QUERY],
            $data[self::FIELD_VARIABLES],
            $data[self::FIELD_OPERATION_NAME],
        );
    }

    public function createRequestFromAdapter(AdapterInterface $adapter): iterable
    {

    }
}
