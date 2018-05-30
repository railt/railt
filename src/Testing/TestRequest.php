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
use Railt\Http\Request;

/**
 * Class TestRequest
 */
abstract class TestRequest implements TestRequestInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * TestServer constructor.
     */
    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @param string $query
     * @return TestQuery
     */
    public function query(string $query): TestQuery
    {
        return new TestQuery($this, $query, 'query');
    }

    /**
     * @param string $query
     * @return TestQuery
     */
    public function mutation(string $query): TestQuery
    {
        return new TestQuery($this, $query, 'mutation');
    }

    /**
     * @param string $query
     * @return TestQuery
     */
    public function subscription(string $query): TestQuery
    {
        return new TestQuery($this, $query, 'subscription');
    }

    /**
     * @param QueryInterface $query
     * @return TestRequestInterface
     */
    public function addQuery(QueryInterface $query): TestRequestInterface
    {
        $this->request->addQuery($query);

        return $this;
    }
}
