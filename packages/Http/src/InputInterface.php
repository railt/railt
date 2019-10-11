<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Input\ProvidesArgumentsInterface;

/**
 * Interface InputInterface
 */
interface InputInterface extends
    ProvidesPathInterface,
    ProvidesTypeInfoInterface,
    ProvidesArgumentsInterface
    // TODO
{
    /**
     * @return RequestInterface
     */
    public function request(): RequestInterface;
}
