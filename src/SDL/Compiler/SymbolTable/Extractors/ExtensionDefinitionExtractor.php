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
use Railt\SDL\Compiler\Exceptions\CompilerException;
use Railt\SDL\Compiler\SymbolTable\Record;
use Railt\SDL\Compiler\Type;

/**
 * Class TypeDefinitionExtractor
 */
class ExtensionDefinitionExtractor extends TypeDefinitionExtractor
{
    /**
     * Extension keyword length
     */
    protected const EXTEND_KEYWORD_OFFSET = 7;

    /**
     * @param Readable $input
     * @param RuleInterface $rule
     * @return Record
     * @throws \Railt\SDL\Compiler\Exceptions\CompilerException
     */
    public function extract(Readable $input, RuleInterface $rule): Record
    {
        $result = parent::extract($input, \array_first($rule->getChildren()));
        $offset = $result->getOffset() - self::EXTEND_KEYWORD_OFFSET;
        $type   = $this->getType($result->getType());

        return new Record($result->getName(), $type, $offset, $input);
    }

    /**
     * @param string $innerTypeName
     * @return string
     * @throws \Railt\SDL\Compiler\Exceptions\CompilerException
     */
    private function getType(string $innerTypeName): string
    {
        switch ($innerTypeName) {
            case Type::OBJECT:
                return Type::EXTENSION_OBJECT;
            case Type::SCHEMA:
                return Type::EXTENSION_SCHEMA;
            case Type::INTERFACE:
                return Type::EXTENSION_INTERFACE;
            case Type::DIRECTIVE:
                return Type::EXTENSION_DIRECTIVE;
            case Type::INPUT:
                return Type::EXTENSION_INPUT;
            case Type::ENUM:
                return Type::EXTENSION_ENUM;
            case Type::UNION:
                return Type::EXTENSION_UNION;
            case Type::SCALAR:
                return Type::EXTENSION_SCALAR;
        }

        $error = 'Could not transform name from %s to extension';
        throw new CompilerException(\sprintf($error, $innerTypeName));
    }

    /**
     * @return array
     */
    protected function getAstNodeNames(): array
    {
        return ['#ExtendDefinition'];
    }
}
