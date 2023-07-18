<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Exception;

use Phplrt\Contracts\Position\PositionInterface;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;

final class Patcher
{
    private \Throwable $throwable;

    public function __construct(\Throwable $throwable)
    {
        $this->throwable = $throwable;
    }

    public static function for(\Throwable $e): self
    {
        return new self($e);
    }

    /**
     * @param callable():void $ctx
     */
    private function set(callable $ctx): self
    {
        \Closure::fromCallable($ctx)
            ->call($this->throwable)
        ;

        return $this;
    }

    public function withAddedMessage(string $message): self
    {
        return $this->withMessage($this->throwable->getMessage() . $message);
    }

    public function withMessage(string $message): self
    {
        return $this->set(function () use ($message): void {
            $this->message = $message;
        });
    }

    public function withSourceAndPosition(ReadableInterface $src, PositionInterface $pos): self
    {
        return $this->withSourceAndLine($src, $pos->getLine());
    }

    /**
     * @param int<1, max> $line
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
     */
    public function withFileAndLine(string $file, int $line): self
    {
        return $this->set(function () use ($file, $line): void {
            $this->file = $file;
            $this->line = $line;
        });
    }
}
