<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\SubProtocol;

use Railt\Http\Request;
use Railt\Http\RequestInterface;
use Railt\Http\Resolver\ResolverInterface;
use Railt\Http\ResponseInterface;
use Railt\Subscriptions\Message\MessageInterface;
use Railt\Subscriptions\Message\NoticeMessage;
use Railt\Subscriptions\Message\PingMessage;
use Railt\Subscriptions\Message\ResponseMessage;

/**
 * Class GraphQLWSProtocol
 */
class GraphQLWSProtocol extends BaseProtocol
{
    /**
     * @var array|\Closure[]
     */
    private $listeners = [];

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'graphql-ws';
    }

    /**
     * @param MessageInterface $message
     */
    public function handle(MessageInterface $message): void
    {
        switch (true) {
            // Type "start"
            case $message->is(MessageInterface::TYPE_START):
                $this->connection->listen($this->toRequest($message), function (ResponseInterface $response): void {
                    $this->answer(new ResponseMessage($response));
                });

                break;

            // Type "stop"
            case $message->is(MessageInterface::TYPE_STOP):
                $this->connection->close();
                break;

            // Otherwise
            default:
                $response = new NoticeMessage($message->getId(), 'Unrecognized incoming message type');
                $this->answer($response);
        }
    }

    /**
     * @param MessageInterface $message
     * @return RequestInterface
     */
    protected function toRequest(MessageInterface $message): RequestInterface
    {
        $payload = $message->get(ResponseMessage::FIELD_PAYLOAD);

        $arguments = [
            $payload[ResolverInterface::QUERY_ARGUMENT] ?? '',
            $payload[ResolverInterface::VARIABLES_ARGUMENT] ?? [],
            $payload[ResolverInterface::OPERATION_ARGUMENT] ?? null,
        ];

        $request = new Request(...$arguments);
        $request->withId($message->getId());

        return $request;
    }

    /**
     * @param MessageInterface $message
     */
    protected function answer(MessageInterface $message): void
    {
        foreach ($this->listeners as $listener) {
            $listener($message);
        }
    }

    /**
     * @param \Closure $then
     */
    public function onAnswer(\Closure $then): void
    {
        $this->listeners[] = $then;
    }

    /**
     * @return void
     */
    public function notify(): void
    {
        $this->answer(new PingMessage());
    }

    /**
     * @return void
     */
    public function close(): void
    {
        // GC optimisation
        $this->listeners = [];

        $this->connection->close();
    }
}
