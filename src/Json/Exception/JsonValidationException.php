<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Exception;

/**
 * Class JsonValidationException
 */
class JsonValidationException extends JsonException implements JsonValidationExceptionInterface
{
    /**
     * @var array|string[]
     */
    private $path;

    /**
     * JsonValidationException constructor.
     *
     * @param string $message
     * @param array $path
     */
    public function __construct(string $message = '', array $path = [])
    {
        $this->path = \array_filter($path);

        parent::__construct($message);
    }

    /**
     * @param string $implode
     * @return string
     */
    public function getPathString(string $implode = '.'): string
    {
        return \implode($implode, $this->path);
    }

    /**
     * @return array
     */
    public function getPath(): array
    {
        return $this->path;
    }
}
