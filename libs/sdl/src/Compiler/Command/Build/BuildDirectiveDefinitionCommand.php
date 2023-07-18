<?php

declare(strict_types=1);

namespace Railt\SDL\Compiler\Command\Build;

use Railt\SDL\Compiler\Command\BuildCommand;
use Railt\SDL\Exception\CompilationException;
use Railt\SDL\Node\IdentifierNode;
use Railt\SDL\Node\Statement\Definition\DirectiveDefinitionNode;
use Railt\TypeSystem\DirectiveDefinition;
use Railt\TypeSystem\DirectiveLocation;

/**
 * @template-extends BuildCommand<DirectiveDefinitionNode, DirectiveDefinition>
 */
final class BuildDirectiveDefinitionCommand extends BuildCommand
{
    public function exec(): void
    {
        // Add "repeatable" attribute
        $this->definition->setIsRepeatable($this->node->isRepeatable());

        // Add directive locations
        foreach ($this->node->locations as $node) {
            $location = $this->buildLocation($node, $this->definition);

            $this->definition->addLocation($location);
        }

        foreach ($this->node->arguments as $node) {
            $this->ctx->push(new BuildArgumentDefinitionCommand(
                ctx: $this->ctx,
                node: $node,
                definition: $this->definition,
            ));
        }
    }

    private function buildLocation(IdentifierNode $node, DirectiveDefinition $parent): DirectiveLocation
    {
        $location = DirectiveLocation::tryFromName($node->value);

        if ($location === null) {
            $message = \sprintf('Directive location "%s" is not valid', $node->value);
            throw CompilationException::create($message, $node);
        }

        if ($parent->hasLocation($location)) {
            $message = \vsprintf('Cannot redefine already defined location "%s" on directive "@%s"', [
                $location->name,
                $parent->getName(),
            ]);

            throw CompilationException::create($message, $node);
        }

        return $location;
    }
}
