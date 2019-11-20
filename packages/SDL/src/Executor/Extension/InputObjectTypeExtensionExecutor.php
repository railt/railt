<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Extension;

use GraphQL\TypeSystem\Type\InputObjectType;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Ast\Extension\InputObjectTypeExtensionNode;

/**
 * Class InputObjectTypeExtensionExecutor
 */
class InputObjectTypeExtensionExecutor extends ExtensionExecutor
{
    /**
     * @param NodeInterface|InputObjectTypeExtensionNode $source
     * @return mixed|void|null
     * @throws \RuntimeException
     */
    public function enter(NodeInterface $source)
    {
        if (! $source instanceof InputObjectTypeExtensionNode) {
            return;
        }

        /** @var InputObjectType $target */
        $target = $this->document->getType($source->name->value);

        if (! $target instanceof InputObjectType) {
            // TODO should throw an error
            return;
        }

        if ($source->fields) {
            $fields = $target->getFields();

            foreach ($source->fields as $field) {
                // TODO assert field exists or merge
                $fields[] = $this->build($field);
            }

            $target->setFields($fields);
        }
    }
}
