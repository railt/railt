<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Contracts\Exception\GraphQLExceptionInterface;
use Railt\Http\Common\RenderableInterface;
use Ramsey\Collection\CollectionInterface;
use Ramsey\Collection\Map\TypedMapInterface;

/**
 * Interface ResponseInterface
 */
interface ResponseInterface extends
    RenderableInterface
{
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
     * @return array|null
     */
    public function getData(): ?array;

    /**
     * @return TypedMapInterface|mixed[]
     */
    public function getExtensions(): TypedMapInterface;

    /**
     * @return CollectionInterface|GraphQLExceptionInterface[]
     */
    public function getExceptions(): CollectionInterface;
}
