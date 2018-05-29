<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing;

use Railt\Http\QueryInterface;

/**
 * Interface TestRequestInterface
 */
interface TestRequestInterface
{
    /**
     * @param string $query
     * @param array $variables
     * @param string|null $operationName
     * @return TestResponse
     */
    public function query(string $query, array $variables = [], string $operationName = null): TestResponse;

    /**
     * @param QueryInterface $query
     * @return TestRequestInterface
     */
    public function addQuery(QueryInterface $query): TestRequestInterface;

    /**
     * @return TestResponse
     */
    public function send(): TestResponse;
}
