<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

/**
 * Trait QueryTrait
 */
trait QueryTrait
{
    /**
     * @var string
     */
    protected string $query;

    /**
     * @param string $query
     * @return void
     */
    private function setQuery(string $query): void
    {
        $this->query = \trim($query);
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        \assert(\is_string($this->query));

        return $this->query;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        \assert(\is_string($this->query));

        return \trim($this->query) === '';
    }
}
