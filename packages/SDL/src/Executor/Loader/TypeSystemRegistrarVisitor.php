<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Loader;

use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Document\MutableDocument;
use Railt\SDL\Exception\TypeErrorException;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\Parser\Ast\TypeSystem\TypeDefinitionNode;

/**
 * Class TypeSystemRegistrarVisitor
 */
class TypeSystemRegistrarVisitor extends RegistrarVisitor
{
    /**
     * @var string
     */
    private const ERROR_TYPE_REDEFINITION = 'There can be only one type named "%s"';

    /**
     * @param NodeInterface $node
     * @return void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    public function leave(NodeInterface $node): void
    {
        if ($node instanceof TypeDefinitionNode) {
            $this->registerTypeDefinition($node);
        }
    }

    /**
     * @param TypeDefinitionNode $type
     * @return void
     * @throws TypeErrorException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    private function registerTypeDefinition(TypeDefinitionNode $type): void
    {
        if ($this->document->hasType($type->name->value)) {
            $message = \sprintf(self::ERROR_TYPE_REDEFINITION, $type->name->value);

            throw new TypeErrorException($message, $type);
        }

        $this->mutate(fn (MutableDocument $document) => $document->withType($type));
    }
}
