<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

/**
 * Interface GraphQLLocation
 */
interface GraphQLExceptionLocation
{
    /**
     * @return int
     */
    public function getLine(): int;

    /**
     * @return int
     */
    public function getColumn(): int;
}
