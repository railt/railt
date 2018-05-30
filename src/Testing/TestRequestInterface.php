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
     * @return TestQuery
     */
    public function query(string $query): TestQuery;

    /**
     * @param string $query
     * @return TestQuery
     */
    public function mutation(string $query): TestQuery;

    /**
     * @param string $query
     * @return TestQuery
     */
    public function subscription(string $query): TestQuery;

    /**
     * @param QueryInterface $query
     * @return TestRequestInterface
     */
    public function addQuery(QueryInterface $query): self;

    /**
     * @return TestResponse
     */
    public function send(): TestResponse;
}
