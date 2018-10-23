<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

/**
 * Class GraphQLExceptionLocation
 */
class GraphQLExceptionLocation implements GraphQLExceptionLocationInterface
{
    /**
     * @var int
     */
    private $line;
    /**
     * @var int
     */
    private $column;

    /**
     * GraphQLExceptionLocation constructor.
     * @param int $line
     * @param int $column
     */
    public function __construct(int $line, int $column = 0)
    {
        $this->line   = $line;
        $this->column = $column;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            static::JSON_LINE_KEY   => $this->getLine(),
            static::JSON_COLUMN_KEY => $this->getColumn(),
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
