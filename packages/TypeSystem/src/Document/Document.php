<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem\Document;

use Railt\Parser\Node\DefinitionNode;
use Railt\Parser\Node\TypeSystem\TypeDefinitionNode;
use Railt\Parser\Node\Executable\Definition\DirectiveNode;
use Railt\Parser\Node\TypeSystem\Definition\SchemaDefinitionNode;
use Railt\Parser\Node\TypeSystem\Definition\DirectiveDefinitionNode;

/**
 * Class Document
 */
class Document implements DocumentInterface
{
    /**
     * @var array|SchemaDefinitionNode[]
     */
    protected array $schemas = [];

    /**
     * @var array|DirectiveNode[]
     */
    protected array $directives = [];

    /**
     * @var array|TypeDefinitionNode[]
     */
    protected array $types = [];

    /**
     * @var array|DefinitionNode[]
     */
    protected array $executions = [];

    /**
     * @return array|SchemaDefinitionNode[]
     */
    public function schemas(): array
    {
        return $this->schemas;
    }

    /**
     * @return array|TypeDefinitionNode[]
     */
    public function types(): array
    {
        return \array_values($this->types);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasType(string $name): bool
    {
        return isset($this->types[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool
    {
        return isset($this->directives[$name]);
    }

    /**
     * @return array|DirectiveDefinitionNode[]
     */
    public function directives(): array
    {
        return \array_values($this->directives);
    }

    /**
     * @return array|DefinitionNode[]
     */
    public function executions(): array
    {
        return $this->executions;
    }
}
