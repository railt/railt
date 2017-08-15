<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Exceptions;

/**
 * Interface GraphQLSchemaException
 * @package Railgun\Exceptions
 */
interface GraphQLSchemaException
{
    /**
     * @return int
     */
    public function getCodeColumn(): int;

    /**
     * @return int
     */
    public function getCodeLine(): int;
}
