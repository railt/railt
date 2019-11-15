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
use GraphQL\TypeSystem\Type\InputObjectType;
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
            foreach ($source->fields as $field) {
                // TODO assert field exists or merge
                $target = $target->withField($this->build($field));
            }
        }

        $this->document->addType($target);
    }
}
