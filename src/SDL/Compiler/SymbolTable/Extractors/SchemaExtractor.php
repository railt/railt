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
use Railt\SDL\Compiler\SymbolTable\Context;
use Railt\SDL\Compiler\SymbolTable\Extractors\Support\NameExtractor;
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * Class SchemaExtractor
 */
class SchemaExtractor implements Extractor
{
    use NameExtractor;

    /**
     * @var string Schema default name
     */
    private const DEFAULT_SCHEMA_NAME = 'DefaultSchema';

    /**
     * @param Context $ctx
     * @param RuleInterface $node
     */
    public function extract(Context $ctx, RuleInterface $node): void
    {
        /** @var int $offset */
        $offset = $node->find('T_SCHEMA', 0)->getOffset();

        $record = new Record($this->readName($node), Record::TYPE_SCHEMA, $offset, $node);

        $ctx->addRecord($record);
    }

    /**
     * @param RuleInterface $ast
     * @return string
     */
    private function readName(RuleInterface $ast): string
    {
        $node = $ast->find('#TypeName', 0);

        if ($node) {
            return $this->fqn($node, self::I_NAME);
        }

        return self::DEFAULT_SCHEMA_NAME;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'Schema';
    }
}
