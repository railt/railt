<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Exception;

use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;

final class Patcher
{
    private readonly \ReflectionObject $reflection;

    public function __construct(
        private readonly \Throwable $throwable,
    ) {
        $this->reflection = new \ReflectionObject($throwable);
    }

    public static function for(\Throwable $e): self
    {
        return new self($e);
    }

    /**
     * @throws \ReflectionException
     */
    public function withAddedMessage(string $message): self
    {
        return $this->withMessage($this->throwable->getMessage() . $message);
    }

    /**
     * @throws \ReflectionException
     */
    public function withMessage(string $message): self
    {
        if ($this->reflection->hasProperty('message')) {
            $property = $this->reflection->getProperty('message');
            $property->setValue($this->throwable, $message);
        }

        return $this;
    }

    /**
     * @throws \ReflectionException
     */
    public function withSourceAndPosition(ReadableInterface $src, PositionInterface $pos): self
    {
        return $this->withSourceAndLine($src, $pos->getLine());
    }

    /**
     * @param int<1, max> $line
     *
     * @throws \ReflectionException
     */
    public function withSourceAndLine(ReadableInterface $src, int $line): self
    {
        if ($src instanceof FileInterface) {
            $this->withFileAndLine($src->getPathname(), $line);
        }

        return $this;
    }

    /**
     * @psalm-taint-sink file $file
     * @param non-empty-string $file
     * @param int<1, max> $line
     *
     * @throws \ReflectionException
     */
    public function withFileAndLine(string $file, int $line): self
    {
        if ($this->reflection->hasProperty('file')) {
            $property = $this->reflection->getProperty('file');
            $property->setValue($this->throwable, $file);
        }

        if ($this->reflection->hasProperty('line')) {
            $property = $this->reflection->getProperty('line');
            $property->setValue($this->throwable, $line);
        }

        return $this;
    }
}
