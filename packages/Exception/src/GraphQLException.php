<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Exception;

use Ramsey\Collection\Set;
use Railt\Exception\Location\Collection;
use Ramsey\Collection\CollectionInterface;
use Railt\Exception\Location\LocationInterface;

/**
 * Class GraphQLException
 */
class GraphQLException extends \Exception implements GraphQLExceptionInterface
{
    /**
     * For all errors that reflect the internal state of the application
     * and should not be visible to users, the message should be replaced
     * with this message.
     *
     * @var string
     */
    public const INTERNAL_EXCEPTION_MESSAGE = 'Internal Server Error';

    /**
     * @var string
     */
    protected string $originalMessage;

    /**
     * @var CollectionInterface|LocationInterface[]
     */
    protected CollectionInterface $locations;

    /**
     * @var CollectionInterface|string[]|int[]
     */
    protected CollectionInterface $path;

    /**
     * GraphQLException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $this->originalMessage = $message;

        $this->locations = new Collection();
        $this->path = new Set('scalar');

        parent::__construct(static::INTERNAL_EXCEPTION_MESSAGE, $code, $previous);
    }

    /**
     * {@inheritDoc}
     */
    public function getLocations(): CollectionInterface
    {
        return $this->locations;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): CollectionInterface
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            self::FIELD_MESSAGE   => $this->getMessage(),
            self::FIELD_LOCATIONS => $this->getLocations(),
            self::FIELD_PATH      => $this->getPath(),
        ];
    }
}
