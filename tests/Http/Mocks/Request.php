<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http\Mocks;

use Railt\Http\Request as BaseRequest;

/**
 * Class RequestMock
 */
class Request extends BaseRequest
{
    /**
     * RequestMock constructor.
     * @param string $query
     * @param array $variables
     * @param string|null $operation
     */
    public function __construct(string $query, array $variables = [], string $operation = null)
    {
        $this->data = [
            $this->getQueryArgument()     => $query,
            $this->getVariablesArgument() => $variables,
            $this->getOperationArgument() => $operation,
        ];
    }
}
