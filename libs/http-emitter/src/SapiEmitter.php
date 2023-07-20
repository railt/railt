<?php

declare(strict_types=1);

namespace Railt\Http\Emitter;

use Railt\Contracts\Http\Factory\Exception\SerializingExceptionInterface;
use Railt\Contracts\Http\Factory\ResponseSerializerInterface;
use Railt\Contracts\Http\ResponseInterface;
use Railt\Http\Emitter\Exception\BodyAlreadySentException;
use Railt\Http\Emitter\Exception\BodyEmittingException;
use Railt\Http\Emitter\Exception\HeadersAlreadySentException;
use Railt\Http\Factory\ResponseSerializer;

class SapiEmitter implements EmitterInterface
{
    public function __construct(
        private readonly ResponseSerializerInterface $serializer = new ResponseSerializer(),
        private readonly HeadersBehaviour $headers = HeadersBehaviour::SKIP,
        private readonly BodyBehaviour $body = BodyBehaviour::APPEND,
    ) {
    }

    /**
     * @throws BodyAlreadySentException
     * @throws HeadersAlreadySentException
     */
    public function emit(ResponseInterface $response): void
    {
        $this->emitHeaders($response);
        $this->emitBody($response);
    }

    /**
     * Returns {@see true} in case of headers already been sent
     * or {@see false} instead.
     */
    private function isHeadersSent(): bool
    {
        return \headers_sent();
    }

    /**
     * Returns {@see true} in case of body already been sent
     * or {@see false} instead.
     */
    private function isBodySent(): bool
    {
        return \ob_get_level() > 0 && \ob_get_length() > 0;
    }

    /**
     * Loops through and emits each header as specified
     * to {@see MessageInterface::getHeaders()}.
     *
     * @throws HeadersAlreadySentException
     */
    protected function emitHeaders(ResponseInterface $response): void
    {
        if ($this->isHeadersSent()) {
            if ($this->headers === HeadersBehaviour::ERROR) {
                throw HeadersAlreadySentException::fromAlreadySentState();
            }

            return;
        }

        [$code, $reason] = $this->getStatus($response);

        \http_response_code($code);
        \header("HTTP/1.1 $code $reason", true, $code);
        \header('Content-Type: application/json');
    }

    /**
     * @return array{int<1, max>, non-empty-string}
     */
    private function getStatus(ResponseInterface $response): array
    {
        if ($response->isSuccessful()) {
            return [200, 'OK'];
        }

        return [500, 'Internal Server Error'];
    }

    /**
     * Emits the message body.
     *
     * @throws BodyAlreadySentException
     */
    protected function emitBody(ResponseInterface $response): void
    {
        if ($this->isBodySent()) {
            if ($this->body === BodyBehaviour::ERROR) {
                throw BodyAlreadySentException::fromAlreadySentState();
            }

            if ($this->body === BodyBehaviour::SKIP) {
                return;
            }
        }

        try {
            $json = $this->serializer->toJson($response);
        } catch (SerializingExceptionInterface $e) {
            throw BodyEmittingException::fromSerializationException($e);
        }

        \fwrite(\STDOUT, $json);
        \flush();

        if (\function_exists('\\fastcgi_finish_request')) {
            \fastcgi_finish_request();
        }
    }
}
