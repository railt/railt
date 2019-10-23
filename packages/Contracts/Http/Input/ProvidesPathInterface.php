<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Http\Input;

/**
 * Interface ProvidesPathInterface
 */
interface ProvidesPathInterface
{
    /**
     * Returns the path to the called field.
     *
     * <code>
     *  query Example {
     *      user {
     *          login
     *      }
     *  }
     *
     *  // ...execution...
     *
     *  // For "user" resolver:
     *  $input->path(); // ["user", "login"]
     *
     *  // For "user { login }" resolver:
     *  $input->path(); // ["user", "login"]
     * </code>
     *
     *
     * @return array|string[]
     */
    public function path(): array;
}
