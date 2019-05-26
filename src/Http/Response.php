<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Response\MutableExceptionsProviderTrait;
use Railt\HttpExtension\MutableExtensionProviderTrait;

/**
 * Class Response
 */
class Response implements MutableResponseInterface
{
    use RenderableTrait;
    use MutableExtensionProviderTrait;
    use MutableExceptionsProviderTrait;

    /**
     * @var string
     */
    public const DATA_KEY = 'data';

    /**
     * @var string
     */
    public const ERRORS_KEY = 'errors';

    /**
     * @var string
     */
    public const EXTENSIONS_KEY = 'extensions';

    /**
     * @var array|null
     */
    private $data;

    /**
     * Response constructor.
     *
     * @param array|null $data
     * @param array $exceptions
     */
    public function __construct(array $data = null, array $exceptions = [])
    {
        $this->data = $data;
        $this->exceptions = $exceptions;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            static::ERRORS_KEY     => $this->getExceptions(),
            static::DATA_KEY       => $this->getData(),
            static::EXTENSIONS_KEY => $this->getExtensions(),
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
     * @param array|null $data
     * @return MutableResponseInterface|$this
     */
    public function withData(?array $data): MutableResponseInterface
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInvalid(): bool
    {
        return ! $this->isValid();
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return \count($this->getExceptions()) === 0;
    }
}
