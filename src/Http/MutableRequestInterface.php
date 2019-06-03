<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Request\MutableOperationNameInterface;
use Railt\Http\Request\MutableQueryInterface;
use Railt\Http\Request\MutableVariablesInterface;

/**
 * Interface MutableRequestInterface
 */
interface MutableRequestInterface extends
    RequestInterface,
    MutableQueryInterface,
    MutableVariablesInterface,
    MutableOperationNameInterface
{
}
