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
use Railt\Io\Readable;
use Railt\SDL\Compiler\SymbolTable;

/**
 * Class ImportExtractor
 */
class ImportExtractor extends BaseExtractor
{
    /**
     * @return array|string[]
     */
    protected function getNodeNames(): array
    {
        return ['#ImportDefinition'];
    }

    /**
     * @param SymbolTable $table
     * @param RuleInterface $node
     * @return \Traversable
     */
    public function extract(SymbolTable $table, RuleInterface $node): \Traversable
    {
        [$types, $from] = [[], null];

        foreach ($node->getChildren() as $child) {
            switch (true) {
                case $child->is('#TypeName'):
                    $types[] = $this->fqn($child);
                    break;

                case $child->is('#ImportFrom'):
                    $from = $this->fqn($child->getChild(0));
                    break 2;
            }
        }

        foreach ((array)$types as $type) {
            $table->addLink($type, $from);
        }

        return new \EmptyIterator();
    }
}
