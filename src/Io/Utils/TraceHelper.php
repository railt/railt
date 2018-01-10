<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io\Utils;

use Railt\Io\Readable;

/**
 * Trait Trace
 */
trait TraceHelper
{
    /**
     * @var string
     */
    protected $definitionFile;

    /**
     * @var int
     */
    protected $definitionLine;

    /**
     * @var string|null
     */
    protected $definitionClass;

    /**
     * @return array
     */
    protected function getBacktrace(): array
    {
        $trace = \array_reverse(\debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        foreach ($trace as $i => $data) {
            $found = \is_subclass_of($data['class'] ?? \stdClass::class, Readable::class);

            if ($found) {
                return [
                    $data['file'],
                    $data['line'],
                    ($trace[$i - 1]['class'] ?? $data['class']) ?? null,
                ];
            }
        }

        return ['undefined', 0, null];
    }

    /**
     * @return int
     */
    public function getDefinitionLine(): int
    {
        return $this->definitionLine;
    }

    /**
     * @return string
     */
    public function getDefinitionFileName(): string
    {
        return $this->definitionFile;
    }
}
