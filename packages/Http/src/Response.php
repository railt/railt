<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Exception\Factory;
use Railt\Http\Response\DataProviderTrait;
use Railt\Http\Response\ExceptionsProviderTrait;
use Railt\Http\Extension\MutableExtensionProviderTrait;

/**
 * Class Response
 */
final class Response implements ResponseInterface
{
    use RenderableTrait;
    use DataProviderTrait;
    use ExceptionsProviderTrait;
    use MutableExtensionProviderTrait;

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
        $this->exceptions = \array_map(fn (\Throwable $e) => Factory::create($e), $exceptions);

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
            static::KEY_DATA       => $this->getData(),
            static::KEY_ERRORS     => $this->getExceptions(),
            static::KEY_EXTENSIONS => $this->getExtensions(),
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
