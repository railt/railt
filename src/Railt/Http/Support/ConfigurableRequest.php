<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Support;

use Railt\Http\RequestInterface;

/**
 * Trait ConfigurableRequest
 */
trait ConfigurableRequest
{
    /**
     * @var string
     */
    private $queryArgument = RequestInterface::DEFAULT_QUERY_ARGUMENT;

    /**
     * @var string
     */
    private $variablesArgument = RequestInterface::DEFAULT_VARIABLES_ARGUMENT;

    /**
     * @var string
     */
    private $operationArgument = RequestInterface::DEFAULT_OPERATION_ARGUMENT;

    /**
     * @return string
     */
    public function getQueryArgument(): string
    {
        return $this->queryArgument;
    }

    /**
     * @param string $name
     * @return ConfigurableRequestInterface|ConfigurableRequest
     */
    public function setQueryArgument(string $name): ConfigurableRequestInterface
    {
        $this->queryArgument = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getVariablesArgument(): string
    {
        return $this->variablesArgument;
    }

    /**
     * @param string $name
     * @return ConfigurableRequestInterface|ConfigurableRequest
     */
    public function setVariablesArgument(string $name): ConfigurableRequestInterface
    {
        $this->variablesArgument = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperationArgument(): string
    {
        return $this->operationArgument;
    }

    /**
     * @param string $name
     * @return ConfigurableRequestInterface|ConfigurableRequest
     */
    public function setOperationArgument(string $name): ConfigurableRequestInterface
    {
        $this->operationArgument = $name;

        return $this;
    }
}
