<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

/**
 * Trait ResponseRenderer
 * @mixin Renderable
 */
trait ResponseRenderer
{
    /**
     * @return bool
     */
    abstract public function isDebug(): bool;

    /**
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @return int
     */
    abstract public function getStatusCode(): int;

    /**
     * @return string
     */
    public function render(): string
    {
        $options = \JSON_HEX_TAG | \JSON_HEX_APOS | \JSON_HEX_AMP | \JSON_HEX_QUOT | \JSON_PARTIAL_OUTPUT_ON_ERROR;

        if ($this->isDebug()) {
            $options |= \JSON_PRETTY_PRINT;
        }

        return \json_encode($this->toArray(), $options);
    }

    /**
     * @return void
     */
    public function send(): void
    {
        if (! \headers_sent()) {
            \http_response_code($this->getStatusCode());
            \header('Content-Type: application/json');
            \header('X-GraphQL-Server: Railt');
        }

        echo $this->render();

        \flush();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
