<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Webonyx;

use GraphQL\Error\Error;
use Railt\Http\GraphQLException as GraphQLExceptionInterface;
use Railt\Http\GraphQLExceptionLocation;

/**
 * Class GraphQLException
 */
class GraphQLException extends \InvalidArgumentException implements GraphQLExceptionInterface
{
    /**
     * @var Error
     */
    private $error;

    /**
     * GraphQLException constructor.
     * @param Error $error
     */
    public function __construct(Error $error)
    {
        $this->error = $error;

        parent::__construct($error->getMessage(), $error->getCode(), $error->getPrevious());

        $this->line = $error->getLine();
        $this->file = $error->getFile();
    }

    /**
     * @return iterable|GraphQLExceptionLocation[]
     */
    public function getLocations(): iterable
    {
        foreach ($this->error->getLocations() as $location) {
            yield new class($location->line, $location->column) implements GraphQLExceptionLocation {
                /**
                 * @var int
                 */
                private $line;

                /**
                 * @var int
                 */
                private $column;

                /**
                 * @param int $line
                 * @param int $column
                 */
                public function __construct(int $line, int $column)
                {
                    $this->line   = $line;
                    $this->column = $column;
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
            };
        }
    }

    /**
     * @return iterable|string[]
     */
    public function getPath(): iterable
    {
        return $this->error->getPath() ?? [];
    }
}
