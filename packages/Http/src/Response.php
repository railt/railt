<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Response\DataTrait;
use Railt\Http\Common\RenderableTrait;
use Psr\Http\Message\MessageInterface;
use Railt\Http\Extension\ExtensionsTrait;
use Railt\Http\Exception\ExceptionsTrait;
use Ramsey\Collection\CollectionInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Class Response
 */
final class Response implements ResponseInterface
{
    use DataTrait;
    use ExtensionsTrait;
    use ExceptionsTrait;
    use RenderableTrait {
        jsonSerialize as private _renderAsJson;
    }

    /**
     * Response constructor.
     *
     * @param array|null $data
     * @param array|\Throwable[]|CollectionInterface $exceptions
     * @param array|CollectionInterface $extensions
     */
    public function __construct(array $data = null, iterable $exceptions = [], iterable $extensions = [])
    {
        $this->setData($data);
        $this->setExtensions($extensions);
        $this->setExceptions($exceptions);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $result = \array_filter($this->toArray(),
            fn ($entry) => \is_countable($entry) ? \count($entry) > 0 : (bool)$entry);

        return \count($result) ? $result : [static::FIELD_DATA => null];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::FIELD_DATA       => $this->getData(),
            self::FIELD_EXCEPTIONS => $this->getExceptions(),
            self::FIELD_EXTENSIONS => $this->getExtensions(),
        ];
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->getExceptions()->isEmpty();
    }

    /**
     * @return bool
     */
    public function isInvalid(): bool
    {
        return $this->hasExceptions();
    }

    /**
     * @param PsrResponseInterface|MessageInterface $response
     * @return PsrResponseInterface
     * @throws \RuntimeException
     */
    public function toPsr(PsrResponseInterface $response): PsrResponseInterface
    {
        $response = $response->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(\json_encode($this, \JSON_THROW_ON_ERROR));

        return $response;
    }
}
