<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator\Type;

use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Railt\CodeGenerator\DefinitionGenerator;

/**
 * @property-read NamedTypeInterface $type
 */
abstract class TypeGenerator extends DefinitionGenerator
{
    /**
     * TypeGenerator constructor.
     *
     * @param NamedTypeInterface $type
     * @param array $config
     */
    public function __construct(NamedTypeInterface $type, $config = [])
    {
        parent::__construct($type, $config);
    }
}
