<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Exception;

use Railt\SDL\Exception\RuntimeExceptionInterface;

final class PrettyFormatter implements FormatterInterface
{
    private SourceFormatter $source;

    public function __construct()
    {
        $this->source = new SourceFormatter();
    }

    public function format(RuntimeExceptionInterface $e): RuntimeExceptionInterface
    {
        Patcher::for($e)
            // Set "file" and "line" properties
            ->withSourceAndPosition($e->getSource(), $e->getPosition())
            ->withAddedMessage(\PHP_EOL)
            // Add error source
            ->withAddedMessage($this->source->format($e))
            ->withAddedMessage(\PHP_EOL)
        ;

        return $e;
    }
}
