<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Illuminate\Contracts\Support\Arrayable;
use Railt\Compiler\Exceptions\SchemaException;

/**
 * TODO Convert to factory
 *
 * Class ErrorFormatter
 */
class ErrorFormatter implements Arrayable
{
    /**
     * @var \Throwable
     */
    private $exception;

    /**
     * @var bool
     */
    private $debug;

    /**
     * Error constructor.
     * @param \Throwable $e
     * @param bool $debug
     */
    public function __construct(\Throwable $e, bool $debug = false)
    {
        $this->exception = $e;
        $this->debug     = $debug;
    }

    /**
     * @param \Throwable $e
     * @param bool $debug
     * @return array
     */
    public static function render(\Throwable $e, bool $debug = false): array
    {
        return (new static($e, $debug))->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $exception = $this->exception;
        $result    = $this->renderItem($exception);

        while ($exception->getPrevious()) {
            if (! \array_key_exists('previous', $result)) {
                $result['previous'] = [];
            }

            $result['previous'][] = $this->renderItem($exception);
            $exception            = $exception->getPrevious();
        }

        return $result;
    }

    /**
     * @param \Throwable $e
     * @return array
     */
    private function renderItem(\Throwable $e): array
    {
        $result = [
            'message' => $e->getMessage(),
        ];

        if ($e instanceof SchemaException) {
            $result = [
                'message'   => 'GraphQL SDL Error: ' . $e->getMessage(),
                'locations' => [
                    [
                        'line'   => $e->getLine(),
                        'column' => $e->getColumn(),
                    ],
                ],
            ];
        }

        if ($this->debug) {
            $result['in']    = $e->getFile() . ':' . $e->getLine();
            $result['trace'] = $e instanceof SchemaException
                ? $e->getCompilerTrace()
                : $e->getTraceAsString();
        }

        return $result;
    }
}
