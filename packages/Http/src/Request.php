<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Common\RenderableTrait;
use Railt\Http\Request\OperationNameTrait;
use Railt\Http\Request\QueryTrait;
use Railt\Http\Request\VariablesTrait;

/**
 * Class Request
 */
final class Request implements RequestInterface
{
    use QueryTrait;
    use VariablesTrait;
    use RenderableTrait;
    use OperationNameTrait;

    /**
     * Request constructor.
     *
     * @param string $query
     * @param iterable $variables
     * @param string|null $operationName
     */
    public function __construct(string $query, iterable $variables = [], string $operationName = null)
    {
        $this->setQuery($query);
        $this->setVariables($variables);
        $this->setOperationName($operationName);
    }

    /**
     * @param string $variable
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $variable, $default = null)
    {
        return $this->getVariable($variable, $default);
    }

    /**
     * @param string $variable
     * @return bool
     */
    public function has(string $variable): bool
    {
        return $this->hasVariable($variable);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::FIELD_QUERY          => $this->getQuery(),
            self::FIELD_VARIABLES      => $this->getVariables(),
            self::FIELD_OPERATION_NAME => $this->getOperationName(),
        ];
    }
}
