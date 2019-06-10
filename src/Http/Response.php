<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Extension\MutableExtensionProviderTrait;
use Railt\Http\Response\MutableDataProviderTrait;
use Railt\Http\Response\MutableExceptionsProviderTrait;

/**
 * Class Response
 */
class Response implements MutableResponseInterface
{
    use RenderableTrait;
    use MutableDataProviderTrait;
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
     * Response constructor.
     *
     * @param array|null $data
     * @param array $exceptions
     * @param array $extensions
     */
    public function __construct(array $data = null, array $exceptions = [], array $extensions = [])
    {
        $this->withData($data);
        $this->withException(...$exceptions);

        foreach ($extensions as $name => $value) {
            $this->withExtension($name, $value);
        }
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
