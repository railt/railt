<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Runtime\CallStackRenderer;

use Railt\Component\SDL\Contracts\Definitions\Definition;
use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;

/**
 * Class RecordRenderer
 */
class DefinitionTraceRenderer extends BaseTraceRenderer
{
    /**
     * @var Definition
     */
    private $definition;

    /**
     * RecordRenderer constructor.
     *
     * @param Definition $definition
     */
    public function __construct(Definition $definition)
    {
        $this->definition = $definition;

        $this->file = $definition->getFileName();
        $this->line = $definition->getDeclarationLine();
        $this->column = $definition->getDeclarationColumn();
    }

    /**
     * @param int $position
     * @return string
     */
    public function toTraceString(int $position): string
    {
        $type = $this->definition instanceof TypeDefinition
            ? $this->definition->getTypeName()
            : \get_class($this->definition);

        return \vsprintf('#%d %s(%d): %s("%s")', [
            $position,
            $this->getFile(),
            $this->getLine(),
            $type,
            $this->definition->getName(),
        ]);
    }
}
