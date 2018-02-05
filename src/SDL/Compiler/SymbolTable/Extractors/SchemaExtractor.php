<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable\Extractors;

use Railt\Compiler\Ast\RuleInterface;
use Railt\SDL\Compiler\SymbolTable;
use Railt\SDL\Compiler\SymbolTable\Record;
use Railt\SDL\Compiler\Type;

/**
 * Class SchemaExtractor
 */
class SchemaExtractor extends BaseExtractor
{
    /**
     * @var string Default schema name
     */
    private const DEFAULT_SCHEMA_NAME = 'DefaultSchema';

    /**
     * @param SymbolTable $table
     * @param RuleInterface $node
     * @return \Traversable
     */
    public function extract(SymbolTable $table, RuleInterface $node): \Traversable
    {
        [$offset, $name] = [0, self::DEFAULT_SCHEMA_NAME];

        foreach ($node->getChildren() as $child) {
            switch (true) {
                case $child->is('#TypeName'):
                    $name = $this->fqn($child);
                    break;

                case $child->is('T_SCHEMA'):
                    $offset = $child->getOffset();
                    break;
            }
        }

        yield new Record($name, Type::SCHEMA, $offset);
    }

    /**
     * @return array|string[]
     */
    protected function getNodeNames(): array
    {
        return ['#SchemaDefinition'];
    }
}
