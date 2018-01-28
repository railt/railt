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
use Railt\SDL\Compiler\SymbolTable\Record;
use Railt\SDL\Compiler\Type;

/**
 * Class SchemaDefinitionExtractor
 */
class SchemaDefinitionExtractor extends BaseExtractor
{
    /**
     * The default schema name while schema does not contain a custom name definition.
     */
    private const DEFAULT_SCHEMA_NAME = 'DefaultSchema';

    /**
     * @param RuleInterface $rule
     * @return Record
     */
    public function extract(RuleInterface $rule): Record
    {
        $name   = $this->extractSchemaName($rule);
        $offset = \optional($rule->find('T_SCHEMA'))->getOffset() ?? 0;

        return new Record($name, Type::SCHEMA, $offset, $rule);
    }

    /**
     * @param RuleInterface $rule
     * @return string
     */
    private function extractSchemaName(RuleInterface $rule): string
    {
        if ($name = $rule->find('T_NAME', 1)) {
            return $name->getValue();
        }

        return self::DEFAULT_SCHEMA_NAME;
    }

    /**
     * @return array
     */
    protected function getAstNodeNames(): array
    {
        return ['#SchemaDefinition'];
    }
}
