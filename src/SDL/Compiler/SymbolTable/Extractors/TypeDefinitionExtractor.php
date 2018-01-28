<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\SymbolTable\Extractors;

use Railt\Compiler\Ast\LeafInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\SDL\Compiler\Exceptions\CompilerException;
use Railt\SDL\Compiler\SymbolTable\Record;
use Railt\SDL\Compiler\Type;

/**
 * Class TypeDefinitionExtractor
 */
class TypeDefinitionExtractor extends BaseExtractor
{
    /**
     * Ast node names list
     */
    private const AST_NODES = [
        '#ObjectDefinition'    => Type::OBJECT,
        '#InterfaceDefinition' => Type::INTERFACE,
        '#UnionDefinition'     => Type::UNION,
        '#ScalarDefinition'    => Type::SCALAR,
        '#EnumDefinition'      => Type::ENUM,
        '#InputDefinition'     => Type::INPUT,
        '#DirectiveDefinition' => Type::DIRECTIVE,
    ];

    /**
     * @param RuleInterface $rule
     * @return Record
     */
    public function extract(RuleInterface $rule): Record
    {
        $name = $rule->find('T_NAME', 1);

        if ($name === null) {
            $error = 'Could not extract name from %s';
            throw new CompilerException(\sprintf($error, (string)$rule));
        }

        $offset = $this->offsetAt($rule, $name);
        $type   = self::AST_NODES[$rule->getName()];

        return new Record($name->getValue(), $type, $offset, $rule);
    }

    /**
     * @param RuleInterface $root
     * @param LeafInterface $name
     * @return int
     */
    private function offsetAt(RuleInterface $root, LeafInterface $name): int
    {
        $offset = $name->getOffset();

        switch ($root->getName()) {
            case '#ObjectDefinition':
                return $offset - 5;
            case '#InterfaceDefinition':
                return $offset - 10;
            case '#UnionDefinition':
                return $offset - 6;
            case '#ScalarDefinition':
                return $offset - 7;
            case '#EnumDefinition':
                return $offset - 5;
            case '#InputDefinition':
                return $offset - 6;
            case '#DirectiveDefinition':
                return $offset - 10;
        }

        return $offset;
    }

    /**
     * @return array
     */
    protected function getAstNodeNames(): array
    {
        return \array_keys(self::AST_NODES);
    }
}
