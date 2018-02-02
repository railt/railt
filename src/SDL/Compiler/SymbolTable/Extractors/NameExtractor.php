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
use Railt\SDL\Compiler\Exceptions\CompilerException;

/**
 * Class NameExtractor
 */
class NameExtractor
{
    public const E_INDEX_NAMESPACE = 0x00;
    public const E_INDEX_NAME      = 0x01;

    /**
     * @param RuleInterface $rule
     * @return array
     */
    public static function extract(RuleInterface $rule): array
    {
        foreach ($rule->getChildren() as $type) {
            if ($type->is('#TypeName')) {
                return self::extractFromTypeNameNode($type);
            }
        }

        throw self::readError($rule);
    }

    /**
     * @param RuleInterface $name
     * @return string
     */
    public static function readName(RuleInterface $name): string
    {
        return $name->getChild(0)->getValue();
    }

    private static function extractFromTypeNameNode(RuleInterface $rule): array
    {
        $namespace = [];

        foreach ($rule->getChildren() as $child) {
            if ($child->is('#TypeNamespace')) {
                $namespace = \iterator_to_array(self::extractNamespace($child));
            }

            if ($child->is('#Name')) {
                return [
                    self::E_INDEX_NAMESPACE => \implode(Extractor::T_NAMESPACE_SEPARATOR, $namespace),
                    self::E_INDEX_NAME      => self::readName($child),
                ];
            }
        }

        throw self::readError($rule);
    }

    /**
     * @param RuleInterface $rule
     * @return \Traversable|string[]
     */
    private static function extractNamespace(RuleInterface $rule): \Traversable
    {
        foreach ($rule->getChildren() as $child) {
            yield self::readName($child);
        }
    }

    /**
     * @param RuleInterface $root
     * @return CompilerException
     */
    private static function readError(RuleInterface $root): CompilerException
    {
        $error = 'Could not extract name from "%s".';

        return new CompilerException(\sprintf($error, $root->getName()));
    }
}
