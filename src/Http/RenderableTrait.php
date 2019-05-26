<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Exception\GraphQLException;
use Railt\Json\Json;

/**
 * Trait RenderableTrait
 */
trait RenderableTrait
{
    /**
     * @var int
     */
    protected $jsonEncodingOptions =
        \JSON_HEX_TAG |
        \JSON_HEX_APOS |
        \JSON_HEX_AMP |
        \JSON_HEX_QUOT |
        \JSON_THROW_ON_ERROR;

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            return Json::encode($this->jsonSerialize(), $this->jsonEncodingOptions);
        } catch (\JsonException $e) {
            return $this->jsonEncodingError($e);
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return \array_filter($this->toArray());
    }

    /**
     * @param \JsonException $e
     * @return string
     */
    private function jsonEncodingError(\JsonException $e): string
    {
        try {
            return Json::encode([
                Response::ERRORS_KEY => [
                    [GraphQLException::MESSAGE_KEY => $this->formatErrorMessage($e)],
                ],
            ], $this->jsonEncodingOptions);
        } catch (\JsonException $e) {
            return $this->formatErrorMessage($e);
        }
    }

    /**
     * @param \JsonException $e
     * @return string
     */
    private function formatErrorMessage(\JsonException $e): string
    {
        return 'JSON Encoding Exception: ' . $e->getMessage();
    }
}
