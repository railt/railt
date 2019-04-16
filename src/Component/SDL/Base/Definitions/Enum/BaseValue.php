<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Base\Definitions\Enum;

use Railt\Component\SDL\Base\Dependent\BaseDependent;
use Railt\Component\SDL\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Component\SDL\Contracts\Definitions\Enum\ValueDefinition;
use Railt\Component\SDL\Contracts\Type;

/**
 * Class BaseValue
 */
abstract class BaseValue extends BaseDependent implements ValueDefinition
{
    use BaseDirectivesContainer;

    /**
     * Enum value type name
     */
    protected const TYPE_NAME = Type::ENUM_VALUE;

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string)$this->name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getValue();
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [

        ]);
    }
}
