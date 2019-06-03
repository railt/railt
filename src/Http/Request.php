<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Request\MutableQueryTrait;
use Railt\Http\Request\MutableVariablesTrait;
use Railt\Http\Request\MutableOperationNameTrait;

/**
 * Class Request
 */
class Request implements MutableRequestInterface
{
    use RenderableTrait;
    use MutableQueryTrait;
    use MutableVariablesTrait;
    use MutableOperationNameTrait;

    /**
     * Request constructor.
     *
     * @param string $query
     * @param iterable $variables
     * @param string|null $operationName
     */
    public function __construct(string $query, iterable $variables = [], string $operationName = null)
    {
        $this->withQuery($query);
        $this->withOperation($operationName);
        $this->withVariables($variables);
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
