<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Builder;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;

/**
 * Interface BuilderInterface
 */
interface BuilderInterface
{
    /**
     * @return TypeDefinition
     */
    public function getReflection(): TypeDefinition;

    /**
     * @return mixed|Type|Schema
     */
    public function build();
}
