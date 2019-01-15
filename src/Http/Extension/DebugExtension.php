<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Extension;

use Railt\Http\Exception\GraphQLException;
use Railt\Io\PositionInterface;

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
     * @var \Throwable
     */
    private $exception;

    /**
     * @var int
     */
    private $trace;

    /**
     * DebugExtension constructor.
     * @param \Throwable $exception
     * @param int $trace
     */
    public function __construct(\Throwable $exception, int $trace = self::MAX_TRACE_SIZE)
    {
        $this->exception = $exception;
        $this->trace     = \max(0, $trace);
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return $this->errorToArray($this->exception);
    }

    /**
     * @param \Throwable $error
     * @return array
     */
    private function errorToArray(\Throwable $error): array
    {
        $message = $error instanceof GraphQLException ? $error->getPublicMessage() : $error->getMessage();

        return \array_filter([
            'message'   => $message,
            'exception' => \get_class($error),
            'code'      => $error->getCode(),
            'file'      => $error->getFile(),
            'line'      => $error->getLine(),
            'column'    => $error instanceof PositionInterface ? $error->getColumn() : null,
            'trace'     => \iterator_to_array($this->renderTrace($error)),
        ], function ($item) {
            return $item !== null;
        });
    }

    /**
     * @param \Throwable $error
     * @return \Traversable
     */
    private function renderTrace(\Throwable $error): \Traversable
    {
        $trace = \array_slice(\explode("\n", $error->getTraceAsString()), 0, $this->trace);

        foreach ($trace as $item) {
            yield \preg_replace('/^#\d+\h*/iu', '', $item);
        }
    }
}
