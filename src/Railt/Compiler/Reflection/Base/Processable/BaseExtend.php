<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Processable;

use Railt\Compiler\Reflection\Base\Definitions\BaseDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Compiler\Reflection\Contracts\Processable\ExtendDefinition;

/**
 * Class BaseExtend
 */
abstract class BaseExtend extends BaseDefinition implements ExtendDefinition
{
    /**
     * Extend type name
     */
    protected const TYPE_NAME = 'Extend';

    /**
     * @var TypeDefinition
     */
    protected $type;

    /**
     * @var string
     */
    protected $name = self::TYPE_NAME;

    /**
     * @return TypeDefinition
     */
    public function getRelatedType(): TypeDefinition
    {
        return $this->type;
    }
}
