<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Backend\Linker;

use GraphQL\Contracts\TypeSystem\Type\NamedTypeInterface;
use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Phplrt\Visitor\Visitor;
use Railt\SDL\Backend\Context;
use Railt\SDL\Backend\Context\CompiledTypeContext;
use Railt\SDL\Exception\TypeNotFoundException;
use Railt\SDL\Frontend\Ast\Executable\DirectiveNode;
use Railt\SDL\Frontend\Ast\Type\NamedDirectiveNode;
use Railt\SDL\Frontend\Ast\Type\NamedTypeNode;

/**
 * Class LinkerVisitor
 */
class LinkerVisitor extends Visitor
{
    /**
     * @var string
     */
    private const ERROR_TYPE_NOT_FOUND = 'Type "%s" not found or could not be loaded';

    /**
     * @var string
     */
    private const ERROR_DIRECTIVE_NOT_FOUND = 'Directive "@%s" not found or could not be loaded';

    /**
     * @var Context
     */
    private Context $context;

    /**
     * @var LinkerInterface
     */
    private LinkerInterface $linker;

    /**
     * LinkerVisitor constructor.
     *
     * @param Context $context
     * @param LinkerInterface $linker
     */
    public function __construct(Context $context, LinkerInterface $linker)
    {
        $this->context = $context;
        $this->linker = $linker;
    }

    /**
     * @param NodeInterface $node
     * @return mixed|void|null
     */
    public function leave(NodeInterface $node)
    {
        if ($node instanceof NamedTypeNode) {
            $this->assertTypeExists($node);
        }

        if ($node instanceof DirectiveNode) {
            $this->assertDirectiveExists($node->name);
        }
    }

    /**
     * @param NamedTypeNode $name
     * @return void
     */
    private function assertTypeExists(NamedTypeNode $name): void
    {
        $context = $this->context->getType($name->name->value);

        if ($context === null) {
            $this->loadOr($name->name->value, function () use ($name): void {
                throw $this->typeNotFound($name);
            });
        }
    }

    /**
     * @param string $type
     * @param \Closure $otherwise
     * @return void
     */
    private function loadOr(string $type, \Closure $otherwise): void
    {
        foreach ($this->linker->getAutoloaders() as $autoloader) {
            $result = $autoloader($type);

            switch (true) {
                case $result instanceof NamedTypeInterface:
                    $this->context->addType(new CompiledTypeContext($result));

                    return;
            }
        }

        $otherwise();
    }

    /**
     * @param NamedTypeNode $name
     * @return TypeNotFoundException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    private function typeNotFound(NamedTypeNode $name): TypeNotFoundException
    {
        $error = \sprintf(self::ERROR_TYPE_NOT_FOUND, $name->name->value);

        return new TypeNotFoundException($error, $name);
    }

    /**
     * @param NamedDirectiveNode $name
     * @return void
     */
    private function assertDirectiveExists(NamedDirectiveNode $name): void
    {
        $context = $this->context->getDirective($name->name->value);

        if ($context === null) {
            $this->loadOr('@' . $name->name->value, function () use ($name): void {
                throw $this->directiveNotFound($name);
            });
        }
    }

    /**
     * @param NamedDirectiveNode $name
     * @return TypeNotFoundException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    private function directiveNotFound(NamedDirectiveNode $name): TypeNotFoundException
    {
        $error = \sprintf(self::ERROR_DIRECTIVE_NOT_FOUND, $name->name->value);

        return new TypeNotFoundException($error, $name);
    }
}
