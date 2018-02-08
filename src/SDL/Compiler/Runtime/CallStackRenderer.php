<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Runtime;
use Railt\SDL\Compiler\Runtime\CallStackRenderer\PhpTraceRenderer;
use Railt\SDL\Compiler\Runtime\CallStackRenderer\RecordTraceRenderer;
use Railt\SDL\Compiler\Runtime\CallStackRenderer\TraceRenderer;

/**
 * Class CallStackRenderer
 */
class CallStackRenderer
{
    /**
     * @var array|TraceRenderer[]
     */
    private $trace = [];

    /**
     * @var TraceRenderer
     */
    private $last;

    /**
     * CallStackRenderer constructor.
     * @param CallStackInterface $stack
     * @param array $trace
     */
    public function __construct(CallStackInterface $stack, array $trace)
    {
        $this->extractSdlStack($stack);
        $this->extractPhpStack($trace);
    }

    /**
     * @param TraceRenderer $renderer
     */
    private function add(TraceRenderer $renderer): void
    {
        if ($this->last === null) {
             $this->last = $renderer;
        } else {
            $this->trace[] = $renderer;
        }
    }

    /**
     * @param array $trace
     */
    private function extractPhpStack(array $trace): void
    {
        foreach ($trace as $item) {
            $this->add(new PhpTraceRenderer($item));
        }
    }

    /**
     * @param CallStackInterface $stack
     */
    private function extractSdlStack(CallStackInterface $stack): void
    {
        while ($latest = $stack->pop()) {
            $this->add(new RecordTraceRenderer($latest));
        }
    }

    /**
     * @return TraceRenderer
     */
    public function getLastRenderer(): TraceRenderer
    {
        return $this->last;
    }

    /**
     * @return iterable|TraceRenderer[]
     */
    public function getTrace(): iterable
    {
        return $this->trace;
    }
}
