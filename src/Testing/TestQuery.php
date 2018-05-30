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
use Railt\Testing\Common\MethodsAccess;

/**
 * Class TestQuery
 * @property-read TestRequestInterface $and
 */
class TestQuery
{
    use MethodsAccess;

    /**
     * @var array
     */
    private $variables = [];

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string
     */
    private $operation;

    /**
     * @var string
     */
    private $query;

    /**
     * @var array
     */
    private $queryVariables = [];

    /**
     * @var array|string[]
     */
    private $fragments = [];

    /**
     * @var TestRequestInterface
     */
    private $request;

    /**
     * TestQuery constructor.
     * @param TestRequestInterface $request
     * @param string $query
     * @param string $operation
     */
    public function __construct(TestRequestInterface $request, string $query, string $operation = 'query')
    {
        $this->query = $query;
        $this->operation = $operation;
        $this->request = $request;
    }

    /**
     * @param string $name
     * @param string $type
     * @param mixed $value
     * @return TestQuery
     */
    public function variable(string $name, string $type, $value): TestQuery
    {
        $this->queryVariables[$name] = $type;
        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * @param string $fragment
     * @return TestQuery
     */
    public function fragment(string $fragment): TestQuery
    {
        $this->fragments[] = $fragment;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        if ($this->name === null) {
            $this->name = 'test' . \str_random(16);
        }

        return $this->name;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        $result = $this->operation . ' ' . $this->getName() . ' ';

        if (\count($this->queryVariables)) {
            $result .= '(';
            foreach ($this->queryVariables as $name => $type) {
                $result .= '$' . $name . ': ' . $type;
            }
            $result .= ') ';
        }

        $result .= $this->query . "\n";
        $result .= \implode("\n", $this->fragments);

        return $result;
    }

    /**
     * @return iterable
     */
    public function getVariables(): iterable
    {
        return $this->variables;
    }

    /**
     * @return QueryInterface
     */
    public function toHttpQuery(): QueryInterface
    {
        return new Query($this->getQuery(), $this->getVariables(), $this->getName());
    }

    /**
     * @return TestResponse
     */
    public function send(): TestResponse
    {
        return $this->request->addQuery($this->toHttpQuery())->send();
    }

    /**
     * @return TestRequestInterface
     */
    public function and(): TestRequestInterface
    {
        return $this->request;
    }
}
