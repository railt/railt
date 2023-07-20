<?php

declare(strict_types=1);

namespace Railt\Http\Factory;

use Railt\Contracts\Http\Factory\AdapterInterface;
use Railt\Contracts\Http\Factory\RequestFactoryInterface;
use Railt\Contracts\Http\Factory\RequestParserInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\Http\Factory\Exception\ParsingException;
use Railt\Http\Factory\Parser\ApolloBatchingRequestParser;
use Railt\Http\Factory\Parser\GetHttpRequestParser;
use Railt\Http\Factory\Parser\JsonBodyHttpRequestParser;
use Railt\Http\Factory\Parser\PostHttpRequestParser;

final class RequestParser implements RequestParserInterface
{
    /**
     * @var list<RequestParserInterface>
     */
    private array $pipeline = [];

    /**
     * @param RequestFactoryInterface $requests
     * @param iterable<RequestParserInterface>|null $parsers
     */
    public function __construct(
        private readonly RequestFactoryInterface $requests = new RequestFactory(),
        ?iterable $parsers = null,
    ) {
        foreach ($parsers ?? $this->getDefaultParsers() as $parser) {
            $this->pipeline[] = $parser;
        }
    }

    /**
     * @return iterable<RequestParserInterface>
     */
    private function getDefaultParsers(): iterable
    {
        yield new JsonBodyHttpRequestParser($this->requests);
        yield new ApolloBatchingRequestParser($this->requests);
        yield new PostHttpRequestParser($this->requests);
        yield new GetHttpRequestParser($this->requests);
    }

    public function withParser(RequestParserInterface $parser): self
    {
        $self = clone $this;
        $self->addParser($parser);

        return $self;
    }

    public function addParser(RequestParserInterface $parser): void
    {
        $this->pipeline[] = $parser;
    }

    public function parse(AdapterInterface $adapter): iterable
    {
        $hasRequest = false;

        foreach ($this->pipeline as $parser) {
            foreach ($parser->parse($adapter) as $request) {
                $hasRequest = true;

                yield $request;
            }

            if ($hasRequest) {
                break;
            }
        }
    }

    public function parseOrFail(AdapterInterface $adapter): iterable
    {
        $hasRequest = false;

        foreach ($this->parse($adapter) as $request) {
            $hasRequest = true;

            yield $request;
        }

        if ($hasRequest) {
            return;
        }

        throw ParsingException::fromEmptyRequest();
    }

    public function first(AdapterInterface $adapter): ?RequestInterface
    {
        foreach ($this->parse($adapter) as $request) {
            return $request;
        }

        return null;
    }

    public function firstOrFail(AdapterInterface $adapter): ?RequestInterface
    {
        foreach ($this->parseOrFail($adapter) as $request) {
            return $request;
        }

        return null;
    }
}
