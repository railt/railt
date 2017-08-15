<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Http;

use Illuminate\Contracts\Support\Arrayable;
use Railgun\Exceptions\GraphQLSchemaException;

/**
 * Class ErrorFormatter
 * @package Railgun\Http
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
        $this->debug = $debug;
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

        $result = [
            $this->renderItem($exception),
        ];

        while ($exception->getPrevious()) {
            $result[] = $this->renderItem($exception);
            $exception = $exception->getPrevious();
        }

        return $result;
    }

    /**
     * @param \Throwable $e
     * @return array
     */
    private function renderItem(\Throwable $e)
    {
        $result = [
            'message' => $e->getMessage(),
        ];

        if ($e instanceof GraphQLSchemaException) {
            $result = [
                'message'   => 'GraphQL Schema Error: ' . $e->getMessage(),
                'locations' => [
                    [
                        'line'   => $e->getCodeLine(),
                        'column' => $e->getCodeColumn(),
                    ],
                ],
            ];
        }

        if ($this->debug) {
            $result['in'] = $e->getFile() . ':' . $e->getLine();
            $result['trace'] = $this->renderTrace($e);
        }

        return $result;
    }

    /**
     * @param \Throwable $e
     * @return array
     */
    private function renderTrace(\Throwable $e): array
    {
        $trace = explode("\n", $e->getTraceAsString());

        $trace = array_map(function (string $trace): string {
            $trace = preg_replace('/#\d+\s+/iu', '', $trace);

            return $trace;
        }, $trace);


        $trace = array_filter($trace, function (string $item): bool {
            return $item !== '{main}';
        });

        return $trace;
    }
}
