<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Common\JsonableTrait;
use Railt\Http\Error\LocationsTrait;
use Railt\Http\Common\RenderableTrait;
use Railt\Http\Response\ExtensionsTrait;
use Railt\Contracts\Http\GraphQLErrorInterface;
use Railt\Contracts\Http\Error\SourceLocationInterface;

/**
 * Class GraphQLException
 */
class GraphQLError extends \Exception implements GraphQLErrorInterface
{
    use JsonableTrait;
    use LocationsTrait;
    use ExtensionsTrait;
    use RenderableTrait {
        __toString as private render;
    }

    /**
     * @var string
     */
    public const FIELD_MESSAGE = 'message';

    /**
     * @var string
     */
    public const FIELD_LOCATIONS = 'locations';

    /**
     * @var string
     */
    public const FIELD_PATH = 'path';

    /**
     * @var string
     */
    public const FIELD_EXTENSIONS = 'extensions';

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
     * @var array|string[]|int[]
     */
    protected array $path = [];

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

        parent::__construct(static::INTERNAL_EXCEPTION_MESSAGE, $code, $prev);
    }

    /**
     * @return \Throwable|null
     */
    public function getException(): ?\Throwable
    {
        $exception = $this;

        while ($exception->getPrevious()) {
            $exception = $exception->getPrevious();

            if (! $exception instanceof GraphQLErrorInterface) {
                return $exception;
            }
        }

        return null;
    }

    /**
     * @param \Throwable $e
     * @return static
     */
    public static function fromThrowable(\Throwable $e): self
    {
        return new static($e->getMessage(), $e->getCode(), $e);
    }

    /**
     * @param string $message
     * @param array|SourceLocationInterface[] $locations
     * @param array|string[]|int[] $path
     * @param \Throwable|null $prev
     * @return static
     */
    public static function create(
        string $message = '',
        array $locations = [],
        array $path = [],
        \Throwable $prev = null
    ): self {
        $instance = new static($message, 0, $prev);
        $instance->setLocations($locations);
        $instance->path = $path;

        return $instance;
    }

    /**
     * @return string
     */
    final public function __toString(): string
    {
        return parent::__toString();
    }

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * @return GraphQLErrorInterface
     */
    public function publish(): GraphQLErrorInterface
    {
        $this->public = true;
        $this->message = $this->original;

        return $this;
    }

    /**
     * @return GraphQLErrorInterface
     */
    public function hide(): GraphQLErrorInterface
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
        $result = $this->mapToArray([
            static::FIELD_MESSAGE    => $this->getMessage(),
            static::FIELD_LOCATIONS  => $this->getLocations(),
            static::FIELD_PATH       => $this->getPath(),
        ]);

        if ($this->extensions !== []) {
            $result[static::FIELD_EXTENSIONS] = $this->getExtensions();
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): array
    {
        return $this->path;
    }
}
