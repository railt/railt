<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable;

use Railt\Compiler\Ast\RuleInterface;
use Railt\Io\Readable;
use Railt\SDL\Compiler\Exceptions\CompilerException;
use Railt\SDL\Compiler\SymbolTable;
use Railt\SDL\Compiler\SymbolTable\Extractors\ExtensionDefinitionExtractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\Extractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\SchemaDefinitionExtractor;
use Railt\SDL\Compiler\SymbolTable\Extractors\TypeDefinitionExtractor;

/**
 * Class Builder
 */
class Builder
{
    /**
     * @var RuleInterface
     */
    private $rule;

    /**
     * @var Extractor[]
     */
    private $extractors = [];

    /**
     * @var Readable
     */
    private $input;

    /**
     * HeaderBuilder constructor.
     * @param RuleInterface $rule
     * @param Readable $input
     */
    public function __construct(RuleInterface $rule, Readable $input)
    {
        $this->input = $input;
        $this->rule = $rule;

        $this->addExtractor(new SchemaDefinitionExtractor());
        $this->addExtractor(new TypeDefinitionExtractor());
        $this->addExtractor(new ExtensionDefinitionExtractor());
    }

    /**
     * @param Extractor $extractor
     * @return Builder
     */
    public function addExtractor(Extractor $extractor): self
    {
        $this->extractors[] = $extractor;

        return $this;
    }

    /**
     * @return SymbolTable
     */
    public function build(): SymbolTable
    {
        $table = new SymbolTable($this->input);

        foreach ($this->rule->getChildren() as $child) {
            $table->register($this->resolve($child));
        }

        return $table;
    }

    /**
     * @param RuleInterface $child
     * @return Record
     */
    private function resolve(RuleInterface $child): Record
    {
        foreach ($this->extractors as $extractor) {
            if ($extractor->match($child)) {
                return $extractor->extract($child);
            }
        }

        $error = 'Could not resolve an %s AST node';
        throw new CompilerException(\sprintf($error, $child->getName()));
    }
}
