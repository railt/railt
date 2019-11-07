<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Registrar;

use Phplrt\Visitor\Traverser;
use Phplrt\Contracts\Ast\NodeInterface;
use Railt\SDL\Exception\TypeErrorException;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Ast\Definition\DirectiveDefinitionNode;

/**
 * Class DirectiveDefinition
 */
class DirectiveDefinition extends TypeRegistrar
{
    /**
     * @var string
     */
    private const ERROR_DIRECTIVE_REDEFINITION = 'There can be only one directive named @%s';

    /**
     * @param NodeInterface $directive
     * @return int|void
     * @throws TypeErrorException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function enter(NodeInterface $directive)
    {
        if ($directive instanceof DirectiveDefinitionNode) {
            $this->assertUniqueness($directive);

            $this->registry->directives->put($directive->name->value, $directive);

            //
            // Temporary optimization.
            // If there is an implementation of nested types,
            // then this code should be deleted.
            //
            return Traverser::DONT_TRAVERSE_CHILDREN;
        }
    }

    /**
     * @param DirectiveDefinitionNode $type
     * @return void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    private function assertUniqueness(DirectiveDefinitionNode $type): void
    {
        if ($this->exists($type->name, $this->dictionary->directives, $this->registry->directives)) {
            $message = \sprintf(self::ERROR_DIRECTIVE_REDEFINITION, $type->name->value);

            throw new TypeErrorException($message, $type);
        }
    }
}
