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

/**
 * Class NamespaceExtractor
 */
class NamespaceExtractor extends BaseExtractor
{
    /**
     * @return array|string[]
     */
    protected function getNodeNames(): array
    {
        return ['#NamespaceDefinition'];
    }

    /**
     * @param SymbolTable $table
     * @param RuleInterface $node
     * @return \Traversable
     */
    public function extract(SymbolTable $table, RuleInterface $node): \Traversable
    {
        $table->setNamespace($this->fqn(...NameExtractor::extract($node)));

        return new \EmptyIterator();
    }
}
