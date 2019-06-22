<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Http;

use Railt\Contracts\Http\Request\QueryInterface;
use Railt\Contracts\Http\Request\VariablesInterface;
use Railt\Contracts\Http\Request\OperationNameInterface;

/**
 * Interface RequestInterface
 */
interface RequestInterface extends
    QueryInterface,
    VariablesInterface,
    RenderableInterface,
    OperationNameInterface
{
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
}
