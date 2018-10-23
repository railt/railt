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
 * Class Message
 */
class Message implements MessageInterface
{
    /**
     * @var iterable
     */
    private $data;

    /**
     * @var iterable|\Throwable[]
     */
    private $exceptions = [];

    /**
     * ResponseChunk constructor.
     * @param iterable $data
     * @param iterable|\Throwable[] $exceptions
     */
    public function __construct(iterable $data = [], iterable $exceptions = [])
    {
        $this->data = $data;
        $this->addExceptions($exceptions);
    }

    /**
     * @param iterable|\Throwable[] $exceptions
     */
    public function addExceptions(iterable $exceptions): void
    {
        foreach ($exceptions as $exception) {
            $this->addException($exception);
        }
    }

    /**
     * @param \Throwable $exception
     */
    public function addException(\Throwable $exception): void
    {
        $this->exceptions[] = $exception;
    }

    /**
     * @return iterable|\Throwable[]
     */
    public function getExceptions(): iterable
    {
        return $this->exceptions;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \array_filter([
            static::FIELD_DATA   => $this->getData() ?: null,
            static::FIELD_ERRORS => $this->exceptions ?: null,
        ]);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        // Unwrap iterators
        $this->data = $this->data instanceof \Traversable ? \iterator_to_array($this->data) : $this->data;

        return $this->data;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return \count($this->exceptions) === 0;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return \count($this->exceptions) > 0;
    }
}
