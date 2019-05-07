<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Exception;

/**
 * Interface GraphQLExceptionLocationInterface
 */
interface GraphQLExceptionLocationInterface extends \JsonSerializable
{
    public const JSON_LINE_KEY = 'line';
    public const JSON_COLUMN_KEY = 'column';

    /**
     * @return int
     */
    public function getLine(): int;

    /**
     * @return int
     */
    public function getColumn(): int;
}
