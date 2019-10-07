<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Common\RenderableInterface;
use Railt\Http\Request\ProvidesOperationNameInterface;
use Railt\Http\Request\ProvidesQueryInterface;
use Railt\Http\Request\ProvidesVariablesInterface;

/**
 * Interface RequestInterface
 */
interface RequestInterface extends
    RenderableInterface,
    ProvidesQueryInterface,
    ProvidesVariablesInterface,
    ProvidesOperationNameInterface
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
