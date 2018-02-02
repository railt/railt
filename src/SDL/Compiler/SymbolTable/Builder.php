<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Exceptions\CompilerException;
use Railt\SDL\Compiler\SymbolTable;
use Railt\SDL\Compiler\SymbolTable\Extractors\Extractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\NamespaceExtractor;

/**
 * Class Builder
 */
class Builder
{
    /**
     * @var array|Extractor[]
     */
    private $extractors = [];

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        $this->addExtractor(new NamespaceExtractor());
    }

    /**
     * @param Extractor $extractor
     * @return void
     */
    public function addExtractor(Extractor $extractor): void
    {
        $this->extractors[] = $extractor;
    }

    /**
     * @param NodeInterface $node
     * @return Extractor
     */
    private function extractor(NodeInterface $node): Extractor
    {
        foreach ($this->extractors as $extractor) {
            if ($extractor->match($node)) {
                return $extractor;
            }
        }

        $error = 'The Node "%s" does not match the allowable for unpacking.';
        throw new CompilerException(\sprintf($error, $node->getName()));
    }

    /**
     * @param Readable $input
     * @param RuleInterface $root
     * @return SymbolTable
     */
    public function build(Readable $input, RuleInterface $root): SymbolTable
    {
        $table = new SymbolTable($input);

        foreach ($root->getChildren() as $child) {
            $extractor = $this->extractor($child);

            foreach ($extractor->extract($table, $child) as $record) {
                $table->addRecord($record);
            }
        }

        return $table;
    }
}
