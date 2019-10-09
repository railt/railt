<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Railt\Dumper\Facade;
use Ramsey\Collection\Set;
use Railt\Http\Common\RenderableTrait;
use Railt\Http\Extension\ExtensionsTrait;
use Ramsey\Collection\CollectionInterface;
use Railt\Http\Exception\Location\Location;
use Railt\Http\Extension\ExtensionsCollection;
use Railt\Http\Exception\Location\LocationsTrait;
use Railt\Http\Exception\Location\LocationInterface;
use Railt\Http\Exception\Location\LocationsCollection;
use Railt\Http\Exception\Location\LocationsProviderInterface;

/**
 * Class GraphQLException
 */
class GraphQLException extends \Exception implements GraphQLExceptionInterface
{
    use LocationsTrait;
    use ExtensionsTrait;
    use RenderableTrait {
        __toString as private render;
    }

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
    protected string $original;

    /**
     * @var CollectionInterface|string[]|int[]
     */
    protected CollectionInterface $path;

    /**
     * @var bool
     */
    protected bool $public = false;

    /**
     * GraphQLException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $prev
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $prev = null)
    {
        $this->original = $message;

        $this->setLocations();
        $this->setExtensions();

        $this->path = new Set('scalar');

        if ($prev instanceof \Throwable) {
            $prev = $this->lookup($prev);
        }

        parent::__construct(static::INTERNAL_EXCEPTION_MESSAGE, $code, $prev);
    }

    /**
     * @param \Throwable $exception
     * @return \Throwable
     */
    private function lookup(\Throwable $exception): \Throwable
    {
        while ($exception->getPrevious()) {
            $exception = $exception->getPrevious();

            if ($exception instanceof GraphQLExceptionInterface) {
                return $exception;
            }
        }

        return $exception;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return parent::__toString();
    }

    /**
     * @param string $message
     * @param array $path
     * @param array $locations
     * @return static|GraphQLExceptionInterface
     */
    public static function new(string $message, array $path = [], array $locations = []): self
    {
        $instance = new static($message);
        $instance->path = new Set('scalar', $path);
        $instance->locations = new LocationsCollection($locations);

        return $instance;
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * @return GraphQLExceptionInterface
     */
    public function publish(): GraphQLExceptionInterface
    {
        $this->public = true;
        $this->message = $this->original;

        return $this;
    }

    /**
     * @return GraphQLExceptionInterface
     */
    public function hide(): GraphQLExceptionInterface
    {
        $this->public = false;
        $this->message = static::INTERNAL_EXCEPTION_MESSAGE;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = $this->toArray();

        if (\count($result[static::FIELD_EXTENSIONS] ?? []) === 0) {
            unset($result[static::FIELD_EXTENSIONS]);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            static::FIELD_MESSAGE    => $this->getMessage(),
            static::FIELD_LOCATIONS  => $this->getLocations()->toArray(),
            static::FIELD_PATH       => $this->getPath()->toArray(),
            static::FIELD_EXTENSIONS => $this->getExtensions(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): CollectionInterface
    {
        return $this->path;
    }
}
