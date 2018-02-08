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
use Railt\SDL\Compiler\SymbolTable\Context;
use Railt\SDL\Compiler\SymbolTable\Extractors\Support\NameExtractor;
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * Class ObjectExtractor
 */
abstract class TypeDefinitionExtractor implements Extractor
{
    use NameExtractor;

    /**
     * @param Context $ctx
     * @param RuleInterface $node
     */
    public function extract(Context $ctx, RuleInterface $node): void
    {
        [$offset, $name] = $this->fqn($node->find('#TypeName', 0));

        $offset -= $this->getPrefixLength() - 1;

        $record = new Record($name, $this->getType(), $offset, $node);

        $ctx->addRecord($record);
    }

    /**
     * @return int
     */
    abstract protected function getPrefixLength(): int;

    /**
     * @return string
     */
    abstract protected function getType(): string;
}
