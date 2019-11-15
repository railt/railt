<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Extension;

use GraphQL\TypeSystem\Type\ObjectType;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Ast\Extension\ObjectTypeExtensionNode;

/**
 * Class ObjectTypeExtensionExecutor
 */
class ObjectTypeExtensionExecutor extends ExtensionExecutor
{
    /**
     * @param NodeInterface|ObjectTypeExtensionNode $source
     * @return mixed|void|null
     * @throws \RuntimeException
     */
    public function enter(NodeInterface $source)
    {
        if (! $source instanceof ObjectTypeExtensionNode) {
            return;
        }

        /** @var ObjectType $target */
        $target = $this->document->getType($source->name->value);

        if (! $target instanceof ObjectType) {
            // TODO should throw an error
            return;
        }

        if ($source->fields) {
            foreach ($source->fields as $field) {
                // TODO assert field exists or merge
                $target = $target->withField($this->build($field));
            }
        }

        if ($source->interfaces) {
            foreach ($source->interfaces as $interface) {
                // TODO assert instance of interface
                $target = $target->withInterface($this->fetch($interface->name->value));
            }
        }

        $this->document->addType($target);
    }
}
