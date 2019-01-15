<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Normalization;

use Railt\SDL\Contracts\Definitions\EnumDefinition;
use Railt\SDL\Contracts\Definitions\ScalarDefinition;
use Railt\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Class Normalizer
 */
abstract class Normalizer implements NormalizerInterface
{
    /**
     * @param FieldDefinition $field
     * @return bool
     */
    protected function isList(FieldDefinition $field): bool
    {
        return $field->isList();
    }

    /**
     * @param FieldDefinition $field
     * @return bool
     */
    protected function isSingular(FieldDefinition $field): bool
    {
        return ! $this->isList($field);
    }

    /**
     * @param FieldDefinition $field
     * @return bool
     */
    protected function isScalar(FieldDefinition $field): bool
    {
        $type = $field->getTypeDefinition();

        return $type instanceof ScalarDefinition || $type instanceof EnumDefinition;
    }

    /**
     * @param FieldDefinition $field
     * @return bool
     */
    protected function isComposite(FieldDefinition $field): bool
    {
        return ! $this->isScalar($field);
    }
}
