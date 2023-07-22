<?php

declare(strict_types=1);

namespace Railt\Http\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Railt\Contracts\Http\Factory\RequestFactoryInterface;
use Railt\Contracts\Http\Factory\RequestParserInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Http\Factory\Composite\SelectiveParser;
use Railt\Http\Factory\Exception\ParsingException;
use Railt\Http\Factory\Parser\ApolloBatchingRequestParser;
use Railt\Http\Factory\Parser\GetHttpRequestParser;
use Railt\Http\Factory\Parser\JsonBodyHttpRequestParser;
use Railt\Http\Factory\Parser\PostHttpRequestParser;
use Railt\Http\GraphQLRequest;

final class GraphQLRequestFactory implements
    RequestFactoryInterface,
    CompositeRequestParserInterface
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

    /**
     * @var list<RequestParserInterface>
     */
    private array $parsers = [];

    /**
     * @param iterable<RequestParserInterface>|null $parsers
     */
    public function __construct(?iterable $parsers = null)
    {
        $this->setParsers($parsers ?? $this->getDefaultParsers());
    }

    /**
     * @return iterable<RequestParserInterface>
     */
    private function getDefaultParsers(): iterable
    {
        yield new JsonBodyHttpRequestParser($this);
        yield new ApolloBatchingRequestParser($this);
        yield new PostHttpRequestParser($this);
        yield new GetHttpRequestParser($this);
    }

    /**
     * Mutable equivalent of {@see CompositeRequestParserInterface::withParsers()} method.
     *
     * @param iterable<RequestParserInterface> $parsers
     *@link CompositeRequestParserInterface::withParsers() method description.
     *
     */
    public function setParsers(iterable $parsers): void
    {
        $this->parsers = [];

        foreach ($parsers as $parser) {
            $this->append($parser);
        }
    }

    public function withParsers(iterable $parsers): self
    {
        $self = clone $this;
        $self->setParsers($parsers);

        return $self;
    }

    /**
     * Mutable equivalent of {@see CompositeRequestParserInterface::withPrependedParser()} method.
     *
     * @link CompositeRequestParserInterface::withPrependedParser() method description.
     */
    public function prepend(RequestParserInterface $parser): void
    {
        $this->removeParser($parser);

        $this->parsers = [$parser, ...$this->parsers];
    }

    public function withPrependedParser(RequestParserInterface $parser): self
    {
        $self = clone $this;
        $self->prepend($parser);

        return $self;
    }

    /**
     * Mutable equivalent of {@see CompositeRequestParserInterface::withAppendedParser()} method.
     *
     * @link CompositeRequestParserInterface::withAppendedParser() method description.
     */
    public function append(RequestParserInterface $parser): void
    {
        $this->removeParser($parser);

        $this->parsers[] = $parser;
    }

    public function withAppendedParser(RequestParserInterface $parser): self
    {
        $self = clone $this;
        $self->append($parser);

        return $self;
    }

    /**
     * Mutable equivalent of {@see CompositeRequestParserInterface::withoutParser()} method.
     *
     * @link CompositeRequestParserInterface::withoutParser() method description.
     */
    public function removeParser(RequestParserInterface $parser): void
    {
        foreach ($this->parsers as $i => $actual) {
            if ($actual === $parser) {
                unset($this->parsers[$i]);

                break;
            }
        }
    }

    public function withoutParser(RequestParserInterface $parser): self
    {
        $self = clone $this;
        $self->removeParser($parser);

        return $self;
    }

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

    public function createRequestFromArray(array $data): RequestInterface
    {
        /**
         * @var array{
         *     query: mixed,
         *     variables: mixed,
         *     operationName: mixed,
         *     ...
         * } $data
         */
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

        if (!\is_string($data[self::FIELD_OPERATION_NAME])) {
            throw ParsingException::fromInvalidVariablesField($data[self::FIELD_OPERATION_NAME]);
        }

        return $this->createRequest(
            $data[self::FIELD_QUERY],
            $data[self::FIELD_VARIABLES],
            $data[self::FIELD_OPERATION_NAME],
        );
    }

    public function createFromServerRequest(ServerRequestInterface $request): iterable
    {
        $parser = new SelectiveParser($this->parsers);

        return $parser->createFromServerRequest($request);
    }
}
