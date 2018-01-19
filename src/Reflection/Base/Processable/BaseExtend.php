<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Processable;

use Railt\Reflection\Base\Definitions\BaseDefinition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Processable\ExtendDefinition;
use Railt\Reflection\Contracts\Type;

/**
 * Class BaseExtend
 */
abstract class BaseExtend extends BaseDefinition implements ExtendDefinition
{
    /**
     * Extend type name
     */
    protected const TYPE_NAME = Type::EXTENSION;

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
