<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Response;

use Railt\Http\Exception\GraphQLExceptionInterface;

/**
 * Trait ExceptionsProviderTrait
 */
trait ExceptionsProviderTrait
{
    /**
     * @var array|GraphQLExceptionInterface[]
     */
    protected array $exceptions = [];

    /**
     * @return array|GraphQLExceptionInterface[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
