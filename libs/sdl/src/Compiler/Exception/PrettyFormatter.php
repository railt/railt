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

    private function isExtended(): bool
    {
        return \in_array(\PHP_SAPI, ['cli', 'embedded', 'phpdbg'], true);
    }

    /**
     * @throws \ReflectionException
     */
    public function format(RuntimeExceptionInterface $e): RuntimeExceptionInterface
    {
        $patcher = Patcher::for($e)
            // Set "file" and "line" properties
            ->withSourceAndPosition($e->getSource(), $e->getPosition())
        ;

        if ($this->isExtended()) {
            $patcher->withAddedMessage(\PHP_EOL)
                ->withAddedMessage($this->source->format($e))
            ;
        }

        return $e;
    }
}
