<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Pipeline\PipelineInterface;
use Railt\Http\Pipeline\Handler\RequestBufferHandler;

/**
 * Class Connection
 */
abstract class Connection implements ConnectionInterface
{
    /**
     * @var int
     */
    private static int $lastId = 0;

    /**
     * @var int
     */
    private int $id;

    /**
     * @var bool
     */
    protected bool $closed = false;

    /**
     * @var array|\Closure[]
     */
    private array $subscribers = [];

    /**
     * @var PipelineInterface
     */
    protected PipelineInterface $pipeline;

    /**
     * Connection constructor.
     *
     * @param PipelineInterface $pipeline
     */
    public function __construct(PipelineInterface $pipeline)
    {
        $this->id = ++self::$lastId;
        $this->pipeline = $pipeline;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        return $this->pipeline->send($this, $request);
    }

    /**
     * @param RequestInterface $request
     * @param \Closure $notifier
     * @return \Generator|ResponseInterface[]
     * @throws \Exception
     */
    public function listen(RequestInterface $request, \Closure $notifier): \Generator
    {
        yield $response = $this->handle($request);

        $buffer = new RequestBufferHandler($response);

        while (! $this->closed) {
            try {
                if ($response = $notifier($this, $request)) {
                    yield $this->sendTo($request, $buffer->withResponse($response));
                }
            } catch (\Throwable $error) {
                yield $this->sendTo($request, $buffer->withException($error));
            }
        }
    }

    /**
     * @param RequestInterface $request
     * @param RequestBufferHandler $handler
     * @return ResponseInterface
     */
    private function sendTo(RequestInterface $request, RequestBufferHandler $handler): ResponseInterface
    {
        return $this->pipeline->sendTo($this, $request, $handler);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return void
     */
    public function close(): void
    {
        $this->closed = true;

        foreach ($this->subscribers as $handler) {
            $handler($this);
        }

        $this->dispose();
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * @return void
     */
    private function dispose(): void
    {
        $this->subscribers = [];
    }

    /**
     * @param \Closure $handler
     * @return void
     */
    public function onClose(\Closure $handler): void
    {
        $this->subscribers[] = $handler;
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }
}
