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
     * @var array
     */
    private $data;

    /**
     * @var iterable|\Throwable[]
     */
    private $errors;

    /**
     * ResponseChunk constructor.
     * @param array $data
     * @param array|\Throwable[] $errors
     */
    public function __construct(array $data = [], array $errors = [])
    {
        $this->data   = $data;
        $this->errors = $errors;
    }

    /**
     * @return iterable|\Throwable[]
     */
    public function getExceptions(): iterable
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return \array_filter([
            static::FIELD_DATA   => $this->data ?: null,
            static::FIELD_ERRORS => $this->errors ?: null,
        ]);
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return \count($this->errors) === 0;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return \count($this->errors) > 0;
    }
}
