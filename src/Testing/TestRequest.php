<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing;

use Railt\Http\Query;
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
     * @param array $variables
     * @param string|null $operationName
     * @return TestResponse
     */
    public function query(string $query, array $variables = [], string $operationName = null): TestResponse
    {
        $this->addQuery(new Query($query, $variables, $operationName));

        return $this->send();
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
