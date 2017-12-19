<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters;

use Railt\Compiler\Reflection\Dictionary;
use Railt\Http\RequestInterface;
use Railt\Http\ResponseInterface;
use Railt\Reflection\Contracts\Definitions\SchemaDefinition;

/**
 * Interface AdapterInterface
 */
interface AdapterInterface
{
    /**
     * AdapterInterface constructor.
     * @param Dictionary $dictionary
     * @param SchemaDefinition $schema
     */
    public function __construct(Dictionary $dictionary, SchemaDefinition $schema);

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function request(RequestInterface $request): ResponseInterface;
}
