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
     * @var \SplStack|ProviderInterface[]
     */
    private $providers;

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
     * @param QueryInterface|ProviderInterface|null $queryOrProvider
     */
    public function __construct($queryOrProvider = null)
    {
        $this->providers = new \SplStack();
        $this->queries   = new \SplStack();

        $this->boot($queryOrProvider);
    }

    /**
     * @param QueryInterface|ProviderInterface|null $queryOrProvider
     * @return void
     */
    private function boot($queryOrProvider = null): void
    {
        if ($queryOrProvider instanceof QueryInterface) {
            $this->addQuery($queryOrProvider);
        }

        if ($queryOrProvider instanceof ProviderInterface) {
            $this->addProvider($queryOrProvider);
        }
    }

    /**
     * @param QueryInterface $query
     * @return RequestInterface
     */
    public function addQuery(QueryInterface $query): RequestInterface
    {
        $this->resetMemoization();

        $this->queries->push($query);

        return $this;
    }

    /**
     * @return void
     */
    private function resetMemoization(): void
    {
        $this->isBatched = null;
    }

    /**
     * @param ProviderInterface $provider
     * @return RequestInterface
     */
    public function addProvider(ProviderInterface $provider): RequestInterface
    {
        $this->resetMemoization();

        $this->providers->push($provider);

        return $this;
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
     * @return QueryInterface
     */
    public function first(): QueryInterface
    {
        return $this->getQueries()->current();
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->first()->getQuery();
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
     * @return \Generator|QueryInterface[]
     */
    public function getIterator(): \Generator
    {
        foreach ($this->queries as $query) {
            yield $query;
        }

        foreach ($this->providers as $provider) {
            foreach ($provider->getQueries() as $query) {
                yield $query;
            }
        }
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
