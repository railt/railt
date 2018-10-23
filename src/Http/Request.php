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
 * Class Request
 */
class Request implements RequestInterface
{
    /**
     * @var \SplStack|QueryInterface[]
     */
    private $queries;

    /**
     * @var bool|null
     */
    private $isBatched;

    /**
     * Request constructor.
     * @param QueryInterface|ProviderInterface $queryOrProvider
     */
    public function __construct($queryOrProvider = null)
    {
        \assert($queryOrProvider === null || $queryOrProvider instanceof QueryInterface || $queryOrProvider instanceof ProviderInterface);

        $this->queries = new \SplStack();

        $this->boot($queryOrProvider);
    }

    /**
     * @param QueryInterface|ProviderInterface $queryOrProvider
     * @return void
     */
    private function boot($queryOrProvider): void
    {
        if ($queryOrProvider instanceof QueryInterface) {
            $this->bootFromQuery($queryOrProvider);
        }

        if ($queryOrProvider instanceof ProviderInterface) {
            $this->bootFromProvider($queryOrProvider);
        }
    }

    /**
     * @param QueryInterface $query
     */
    private function bootFromQuery(QueryInterface $query): void
    {
        $this->addQuery($query);
    }

    /**
     * @param QueryInterface $query
     * @return RequestInterface
     */
    public function addQuery(QueryInterface $query): RequestInterface
    {
        $this->queries->push($query);

        return $this;
    }

    /**
     * @param ProviderInterface $provider
     */
    private function bootFromProvider(ProviderInterface $provider): void
    {
        foreach ($provider->getQueries() as $query) {
            $this->addQuery($query);
        }
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->first()->getQuery();
    }

    /**
     * @return QueryInterface
     */
    public function first(): QueryInterface
    {
        return $this->getQueries()->current();
    }

    /**
     * @return iterable|QueryInterface[]|\Generator
     */
    public function getQueries(): iterable
    {
        $queries = $this->getIterator();

        if ($queries->valid()) {
            yield from $queries;
        } else {
            yield Query::empty();
        }
    }

    /**
     * @return \Generator|QueryInterface[]
     */
    public function getIterator(): \Generator
    {
        foreach ($this->queries as $query) {
            yield $query;
        }
    }

    /**
     * @return iterable
     */
    public function getVariables(): iterable
    {
        return $this->first()->getVariables();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getVariable(string $name)
    {
        return $this->first()->getVariable($name);
    }

    /**
     * @return null|string
     */
    public function getOperationName(): ?string
    {
        return $this->first()->getOperationName();
    }

    /**
     * @return bool
     */
    public function isBatched(): bool
    {
        if ($this->isBatched === null) {
            $this->isBatched = \iterator_count($this->getIterator()) > 1;
        }

        return $this->isBatched;
    }
}
