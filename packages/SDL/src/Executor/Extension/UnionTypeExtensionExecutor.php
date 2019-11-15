<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Extension;

use GraphQL\TypeSystem\Type\UnionType;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Ast\Extension\UnionTypeExtensionNode;

/**
 * Class UnionTypeExtensionExecutor
 */
class UnionTypeExtensionExecutor extends ExtensionExecutor
{
    /**
     * @param NodeInterface|UnionTypeExtensionNode $source
     * @return mixed|void|null
     * @throws \RuntimeException
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
            foreach ($source->types as $type) {
                // TODO Assert type
                $target = $target->withType($this->fetch($type->name->value));
            }
        }

        $this->document->addType($target);
    }
}
