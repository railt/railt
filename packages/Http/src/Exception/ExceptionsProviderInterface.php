<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

use Ramsey\Collection\CollectionInterface;

/**
 * Interface ExceptionsProviderInterface
 */
interface ExceptionsProviderInterface
{
    /**
     * @var string
     */
    public const FIELD_EXCEPTIONS = 'errors';

    /**
     * @return CollectionInterface|GraphQLExceptionInterface[]
     */
    public function getExceptions(): CollectionInterface;
}
