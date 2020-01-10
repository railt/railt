<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Extension;

use GraphQL\Contracts\TypeSystem\Type\EnumTypeInterface;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Ast\Extension\EnumTypeExtensionNode;
use Railt\TypeSystem\Type\EnumType;

/**
 * Class EnumTypeExtensionExecutor
 */
class EnumTypeExtensionExecutor extends ExtensionExecutor
{
    /**
     * @param NodeInterface|EnumTypeExtensionNode $source
     * @return mixed|void|null
     */
    public function enter(NodeInterface $source)
    {
        if (! $source instanceof EnumTypeExtensionNode) {
            return;
        }

        /** @var EnumType $target */
        $target = $this->document->getType($source->name->value);

        if (! $target instanceof EnumTypeInterface) {
            // TODO should throw an error
            return;
        }

        $values = $target->getValues();

        foreach ($source->values ?? [] as $value) {
            // TODO assert overriding
            $values[] = $this->build($value);
        }

        $target->addValues($values);
    }
}
