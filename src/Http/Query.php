<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

/**
 * Class Query
 */
class Query implements QueryInterface
{
    /**
     * @var string
     */
    private $query;

    /**
     * @var iterable
     */
    private $vars;

    /**
     * @var string|null
     */
    private $operationName;

    /**
     * Query constructor.
     * @param string $query
     * @param iterable $vars
     * @param string|null $operationName
     */
    public function __construct(string $query, iterable $vars = [], string $operationName = null)
    {
        $this->query = $query;
        $this->vars = $this->fromIterator($vars);
        $this->operationName = $operationName;
    }

    /**
     * @return QueryInterface
     */
    public static function empty(): QueryInterface
    {
        return new static('');
    }

    /**
     * @param iterable $vars
     * @return array
     */
    private function fromIterator(iterable $vars): array
    {
        return $vars instanceof \Traversable ? \iterator_to_array($vars) : $vars;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @return iterable
     */
    public function getVariables(): iterable
    {
        return $this->vars;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function getVariable(string $name)
    {
        /**
         * Support sampling from an array using the helper of Illuminate Framework.
         * @see https://laravel.com/docs/5.6/helpers#method-array-get
         */
        if (\function_exists('\\array_get')) {
            return \array_get($this->vars, $name);
        }

        return $this->vars[$name] ?? null;
    }

    /**
     * @return null|string
     */
    public function getOperationName(): ?string
    {
        return $this->operationName;
    }
}
