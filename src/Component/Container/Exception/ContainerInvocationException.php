<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Container\Exception;

use Psr\Container\ContainerExceptionInterface;

/**
 * Class ContainerInvocationException
 */
class ContainerInvocationException extends \RuntimeException implements ContainerExceptionInterface
{
}
