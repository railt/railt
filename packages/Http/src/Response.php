<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Ramsey\Collection\Set;
use Railt\Http\Common\RenderableTrait;
use Ramsey\Collection\CollectionInterface;
use Ramsey\Collection\Map\TypedMapInterface;
use Railt\Http\Extension\ExtensionsCollection;

/**
 * Class Response
 */
final class Response implements ResponseInterface
{
    use RenderableTrait;

    /**
     * @var array|null
     */
    private ?array $data;

    /**
     * @var TypedMapInterface|mixed[]
     */
    private TypedMapInterface $extensions;

    /**
     * @var CollectionInterface|\Throwable[]
     */
    private CollectionInterface $exceptions;

    /**
     * Response constructor.
     *
     * @param array|null $data
     * @param array $exceptions
     * @param array $extensions
     */
    public function __construct(array $data = null, array $exceptions = [], array $extensions = [])
    {
        $this->data = $data;
        $this->extensions = new ExtensionsCollection($extensions);
        $this->exceptions = new Set(\Throwable::class, $exceptions);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return \array_filter($this->toArray(), $this->filter());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::FIELD_DATA       => $this->getData(),
            self::FIELD_ERRORS     => $this->getExceptions(),
            self::FIELD_EXTENSIONS => $this->getExtensions(),
        ];
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @return CollectionInterface|\Throwable[]
     */
    public function getExceptions(): CollectionInterface
    {
        return $this->exceptions;
    }

    /**
     * @return TypedMapInterface
     */
    public function getExtensions(): TypedMapInterface
    {
        return $this->extensions;
    }

    /**
     * @return \Closure
     */
    private function filter(): \Closure
    {
        return fn ($entry) => \is_iterable($entry) ? \count($entry) > 0 : (bool)$entry;
    }
}
