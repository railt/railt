<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Http;

use Railt\Common\RenderableTrait;
use Railt\Http\Response\DataTrait;
use Railt\Http\Response\ErrorsTrait;
use Railt\Http\Response\ExceptionsTrait;
use Railt\Http\Response\ExtensionsTrait;
use Railt\Contracts\Http\ResponseInterface;

/**
 * Class Response
 */
final class Response implements ResponseInterface
{
    use DataTrait;
    use ErrorsTrait;
    use ExceptionsTrait;
    use ExtensionsTrait;
    use RenderableTrait;

    /**
     * @var string
     */
    public const FIELD_DATA = 'data';

    /**
     * @var string
     */
    public const FIELD_ERRORS = 'errors';

    /**
     * @var string
     */
    public const FIELD_EXTENSIONS = 'extensions';

    /**
     * Response constructor.
     *
     * @param mixed|null $data
     * @param iterable|\Throwable[] $exceptions
     * @param iterable|mixed $extensions
     */
    public function __construct($data = null, iterable $exceptions = [], iterable $extensions = [])
    {
        $this->setData($data);
        $this->setExceptions($exceptions);
        $this->setExtensions($extensions);
    }

    /**
     * @param mixed|null $data
     * @return static
     */
    public static function create($data = null): self
    {
        return new static($data);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = \array_filter($this->mapToArray([
            self::FIELD_ERRORS     => $this->getErrors(),
            self::FIELD_DATA       => $this->getData(),
            self::FIELD_EXTENSIONS => $this->getExtensions(),
        ]));

        if (! isset($result[self::FIELD_ERRORS])) {
            $result[self::FIELD_DATA] = $this->getData();
        }

        return $result;
    }
}
