<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL;

use Railt\Contracts\GraphQL\FactoryInterface;
use Railt\Contracts\Pipeline\Http\HandlerInterface;
use Railt\Contracts\TypeSystem\DocumentInterface;

/**
 * Class Factory
 */
class Factory implements FactoryInterface
{
    /**
     * @param DocumentInterface $document
     * @return HandlerInterface
     */
    public function create(DocumentInterface $document): HandlerInterface
    {
        return new WebonyxExecutorHandler($document);
    }
}
