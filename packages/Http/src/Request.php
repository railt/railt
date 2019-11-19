<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http;

use Railt\Common\RenderableTrait;
use Railt\Http\Request\QueryTrait;
use Railt\Http\Request\VariablesTrait;
use Railt\Contracts\Http\RequestInterface;
use Railt\Http\Request\OperationNameTrait;

/**
 * Class Request
 */
final class Request implements RequestInterface
{
    use QueryTrait;
    use VariablesTrait;
    use OperationNameTrait;
    use RenderableTrait;

    /**
     * @var string
     */
    public const FIELD_QUERY = 'query';

    /**
     * @var string
     */
    public const FIELD_VARIABLES = 'variables';

    /**
     * @var string
     */
    public const FIELD_OPERATION_NAME = 'operationName';

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
     * @return array
     */
    public function toArray(): array
    {
        return $this->mapToArray([
            self::FIELD_QUERY          => $this->getQuery(),
            self::FIELD_VARIABLES      => $this->getVariables(),
            self::FIELD_OPERATION_NAME => $this->getOperationName(),
        ]);
    }
}
