<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Building;

use Railt\Component\SDL\Contracts\Definitions\InputDefinition;
use Railt\Component\SDL\Contracts\Dependent\ArgumentDefinition;
use Railt\Component\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Class TypeBuilding
 */
class TypeBuilding extends BuildingEvent
{
    /**
     * @return bool
     */
    public function isField(): bool
    {
        return $this->getTypeDefinition() instanceof FieldDefinition;
    }

    /**
     * @return bool
     */
    public function isArgument(): bool
    {
        $type = $this->getTypeDefinition();

        return $type instanceof ArgumentDefinition && ! $type->getParent() instanceof InputDefinition;
    }

    /**
     * @return bool
     */
    public function isInputField(): bool
    {
        $type = $this->getTypeDefinition();

        return $type instanceof ArgumentDefinition && $type->getParent() instanceof InputDefinition;
    }
}
