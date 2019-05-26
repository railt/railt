<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Request\QueryTypeInterface;
use Railt\Http\Request\VariablesInterface;
use Railt\Http\Request\OperationNameInterface;

/**
 * Interface RequestInterface
 */
interface RequestInterface extends
    VariablesInterface,
    QueryTypeInterface,
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

    /**
     * @return string
     */
    public function getQuery(): string;

    /**
     * @return bool
     */
    public function isEmpty(): bool;
}
