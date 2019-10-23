<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Document;

use Railt\Parser\Ast\DefinitionNode;
use Railt\Parser\Ast\TypeSystem\Definition\DirectiveDefinitionNode;
use Railt\Parser\Ast\TypeSystem\Definition\SchemaDefinitionNode;
use Railt\Parser\Ast\TypeSystem\TypeDefinitionNode;

/**
 * Class MutableTypeSystemDocument
 */
class MutableDocument extends Document
{
    /**
     * @param SchemaDefinitionNode $schema
     * @return MutableDocument|$this
     */
    public function withSchema(SchemaDefinitionNode $schema): self
    {
        $this->schemas[] = $schema;

        return $this;
    }

    /**
     * @param DirectiveDefinitionNode $directive
     * @return MutableDocument|$this
     */
    public function withDirective(DirectiveDefinitionNode $directive): self
    {
        $this->directives[$directive->name->value] = $directive;

        return $this;
    }

    /**
     * @param TypeDefinitionNode $type
     * @return MutableDocument|$this
     */
    public function withType(TypeDefinitionNode $type): self
    {
        $this->types[$type->name->value] = $type;

        return $this;
    }

    /**
     * @param DefinitionNode $directive
     * @return MutableDocument|$this
     */
    public function withExecution(DefinitionNode $directive): self
    {
        $this->executions[] = $directive;

        return $this;
    }
}
