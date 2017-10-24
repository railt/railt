<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Dependent;

use Railt\Compiler\Reflection\Base\Definitions\BaseDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Dependent\DependentDefinition;

/**
 * Class BaseDependentType
 */
abstract class BaseDependent extends BaseDefinition implements DependentDefinition
{
    /**
     * @var Definition|mixed
     */
    protected $parent;

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'parent'
        ]);
    }
}
