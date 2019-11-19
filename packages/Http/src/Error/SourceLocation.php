<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http\Error;

use Railt\Common\RenderableTrait;
use Railt\Contracts\Http\Error\SourceLocationInterface;

/**
 * Class Location
 */
final class SourceLocation implements SourceLocationInterface
{
    use RenderableTrait;

    /**
     * @var int
     */
    private int $line;

    /**
     * @var int
     */
    private int $column;

    /**
     * GraphQLExceptionLocation constructor.
     *
     * @param int $line
     * @param int $column
     */
    public function __construct(int $line, int $column = 1)
    {
        $this->line = \max(1, $line);
        $this->column = \max(1, $column);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            static::FIELD_LINE => $this->getLine(),
            static::FIELD_COLUMN => $this->getColumn(),
        ];
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }
}
