<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Http;

use Railt\Contracts\Http\Input\ProvidesArgumentsInterface;
use Railt\Contracts\Http\Input\ProvidesPathInterface;
use Railt\Contracts\Http\Input\ProvidesTypeInfoInterface;

/**
 * Interface InputInterface
 */
interface InputInterface extends
    ProvidesPathInterface,
    ProvidesTypeInfoInterface,
    ProvidesArgumentsInterface
{
}
