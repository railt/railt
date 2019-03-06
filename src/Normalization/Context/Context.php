<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Normalization\Context;

use Railt\SDL\Contracts\Definitions\EnumDefinition;
use Railt\SDL\Contracts\Definitions\ScalarDefinition;
use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Class Context
 */
class Context implements ContextInterface
{
    /**
     * @var FieldDefinition
     */
    protected $field;

    /**
     * Context constructor.
     *
     * @param FieldDefinition $field
     */
    public function __construct(FieldDefinition $field)
    {
        $this->field = $field;
    }

    /**
     * @return Slice
     * @throws \LogicException
     */
    public function getItemContext(): Slice
    {
        if ($this->isList()) {
            return new Slice($this->field);
        }

        $error = 'Unable to get the context of a list item, because parent %s is not a list';
        throw new \LogicException(\sprintf($error, $this->field));
    }

    /**
     * @return TypeDefinition
     */
    public function getType(): TypeDefinition
    {
        return $this->field->getTypeDefinition();
    }

    /**
     * @return bool
     */
    public function isScalar(): bool
    {
        $type = $this->getType();

        return $type instanceof ScalarDefinition || $type instanceof EnumDefinition;
    }

    /**
     * @return bool
     */
    public function isList(): bool
    {
        return $this->field->isList();
    }

    /**
     * @return bool
     */
    public function isNonNull(): bool
    {
        return $this->field->isNonNull();
    }
}
