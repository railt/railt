<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug\Http;

use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Http\Extension\ExtensionInterface;

/**
 * Class TracingExtension
 */
class TracingExtension implements ExtensionInterface
{
    /**
     * @var string
     */
    private const TRACING_VERSION = '1';

    /**
     * @var float|int
     */
    private $start;

    /**
     * @var array|array[]
     */
    private $resolvers = [];

    /**
     * @var array|int[]
     */
    private $validation = [
        'startOffset' => 0,
        'duration'    => 0,
    ];

    /**
     * @var array|int[]
     */
    private $parsing = [
        'startOffset' => 0,
        'duration'    => 0,
    ];

    /**
     * TracingExtension constructor.
     */
    public function __construct()
    {
        $this->start = $this->now();
    }

    /**
     * @return float
     */
    private function now(): float
    {
        return \microtime(true);
    }

    /**
     * @param float $start
     * @param float $end
     * @return float
     */
    private function duration(float $start, float $end): float
    {
        return $end - $start;
    }

    /**
     * @param FieldResolve $event
     */
    public function before(FieldResolve $event): void
    {
        $input = $event->getInput();

        $this->resolvers[$input->getPath()] = [
            'path'        => $input->getPathChunks(),
            'parentType'  => $input->getTypeName(),
            'fieldName'   => $input->getField(),
            'returnType'  => $input->getPreferType(),
            'startOffset' => $this->durationFromStart($this->now()),
            'duration'    => 0,
        ];
    }

    /**
     * @param float $end
     * @return float
     */
    private function durationFromStart(float $end): float
    {
        return $this->duration($this->start, $end);
    }

    /**
     * @param FieldResolve $event
     */
    public function after(FieldResolve $event): void
    {
        if (isset($this->resolvers[$event->getPath()])) {
            $execution = $this->resolvers[$event->getPath()];

            $offset = $execution['startOffset'] + $this->start;

            $execution['duration'] = $this->duration($offset, $this->now()) * 1000;

            $this->resolvers[$event->getPath()] = $execution;
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getValue(): array
    {
        $end = $this->now();

        return [
            'tracing' => [
                'version'    => self::TRACING_VERSION,
                'startTime'  => $this->date($this->start),
                'endTime'    => $this->date($end),
                'duration'   => $this->ms($this->durationFromStart($end)),
                'parsing'    => $this->parsing,
                'validation' => $this->validation,
                'execution'  => [
                    'resolvers' => \array_map(function (array $execution): array {
                        $execution['duration'] = $this->ms($execution['duration']);
                        $execution['startOffset'] = $this->ms($execution['startOffset']);

                        return $execution;
                    }, \array_values($this->resolvers)),
                ],
            ],
        ];
    }

    /**
     * @param float $ms
     * @return string
     */
    private function date(float $ms): string
    {
        $date = \DateTime::createFromFormat('U.u', (string)$ms);

        return $date->format('Y-m-d\TH:i:s.uP');
    }

    /**
     * @param float $ms
     * @return int
     */
    private function ms(float $ms): int
    {
        return (int)($ms * 1000);
    }
}
