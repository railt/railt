<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception\Extension;

/**
 * Class DebugExtension
 */
class DebugExtension extends Extension
{
    /**
     * @var int
     */
    private const MAX_TRACE_SIZE = 80;

    /**
     * @var \Throwable[]
     */
    private $exceptions = [];

    /**
     * @var int
     */
    private $trace;

    /**
     * DebugExtension constructor.
     * @param \Throwable $error
     * @param int $trace
     */
    public function __construct(\Throwable $error, int $trace = self::MAX_TRACE_SIZE)
    {
        $this->exceptions[] = $error;
        $this->trace = \max(0, $trace);
    }

    /**
     * @param \Throwable $error
     * @return DebugExtension
     */
    public function addException(\Throwable $error): self
    {
        $this->exceptions[] = $error;

        return $this;
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        $errors = [];

        foreach ($this->exceptions as $exception) {
            do {
                $errors[] = $this->errorToArray($exception);
            } while ($exception = $exception->getPrevious());
        }

        return $errors;
    }

    /**
     * @param \Throwable $error
     * @return array
     */
    private function errorToArray(\Throwable $error): array
    {
        $trace = \array_slice(\explode("\n", $error->getTraceAsString()), 0, $this->trace);

        return [
            'message' => $error->getMessage(),
            'file'    => $error->getFile() . ':' . $error->getLine(),
            'trace'   => $trace,
        ];
    }
}
