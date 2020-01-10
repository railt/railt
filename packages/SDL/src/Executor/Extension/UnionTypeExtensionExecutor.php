<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Extension;

use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Ast\Extension\UnionTypeExtensionNode;
use Railt\TypeSystem\Type\UnionType;

/**
 * Class UnionTypeExtensionExecutor
 */
class UnionTypeExtensionExecutor extends ExtensionExecutor
{
    /**
     * @param NodeInterface|UnionTypeExtensionNode $source
     * @return mixed|void|null
     */
    public function enter(NodeInterface $source)
    {
        if (! $source instanceof UnionTypeExtensionNode) {
            return;
        }

        /** @var UnionType $target */
        $target = $this->document->getType($source->name->value);

        if (! $target instanceof UnionType) {
            // TODO should throw an error
            return;
        }

        if ($source->types) {
            $types = $target->getTypes();

            foreach ($source->types as $type) {
                // TODO Assert type
                $types[] = $this->fetch($type->name->value);
            }

            $target->setTypes($types);
        }
    }
}
