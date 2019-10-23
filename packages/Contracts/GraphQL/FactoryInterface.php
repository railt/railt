<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\GraphQL;

use Railt\Contracts\Pipeline\Http\HandlerInterface;
use Railt\Contracts\TypeSystem\DocumentInterface;

/**
 * Interface FactoryInterface
 */
interface FactoryInterface
{
    /**
     * @param DocumentInterface $document
     * @return HandlerInterface
     */
    public function create(DocumentInterface $document): HandlerInterface;
}
