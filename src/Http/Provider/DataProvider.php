<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider;

use Railt\Http\QueryInterface;

/**
 * Class DataProvider
 */
class DataProvider implements ProviderInterface
{
    /**
     * @var array|QueryInterface[]
     */
    private $queries = [];

    /**
     * @param QueryInterface $query
     * @return $this
     */
    public function addQuery(QueryInterface $query): self
    {
        $this->queries[] = $query;

        return $this;
    }

    /**
     * @return iterable
     */
    public function getQueries(): iterable
    {
        return $this->queries;
    }
}
