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
 * Trait ExceptionsTrait
 *
 * @mixin ExceptionsProviderInterface
 */
trait ExceptionsTrait
{
    /**
     * @var CollectionInterface|\Throwable[]
     */
    private CollectionInterface $exceptions;

    /**
     * @param array|\Throwable[] $exceptions
     * @return void
     */
    protected function setExceptions(array $exceptions): void
    {
        $this->exceptions = new ExceptionsCollection($exceptions);
    }

    /**
     * @return CollectionInterface|GraphQLExceptionInterface[]
     */
    public function getExceptions(): CollectionInterface
    {
        return $this->exceptions;
    }
}
