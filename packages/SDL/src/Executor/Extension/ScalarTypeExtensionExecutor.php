<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Extension;

use GraphQL\TypeSystem\Type\ScalarType;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Ast\Extension\ScalarTypeExtensionNode;

/**
 * Class ScalarTypeExtensionExecutor
 */
class ScalarTypeExtensionExecutor extends ExtensionExecutor
{
    /**
     * @param NodeInterface|ScalarTypeExtensionNode $source
     * @return mixed|void|null
     */
    public function enter(NodeInterface $source)
    {
        if (! $source instanceof ScalarTypeExtensionNode) {
            return;
        }

        /** @var ScalarType $target */
        $target = $this->document->getType($source->name->value);

        if (! $target instanceof ScalarType) {
            // TODO should throw an error
            return;
        }
    }
}
