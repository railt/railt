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

/**
 * Class ImportExtractor
 */
class ImportExtractor implements Extractor
{
    use NameExtractor;

    /**
     * @param Context $ctx
     * @param RuleInterface $node
     */
    public function extract(Context $ctx, RuleInterface $node): void
    {
        $targets = [];

        foreach ($node->find('#ImportTarget', 0)->getChildren() as $target) {
            $targets[] = $this->fqn($target, self::I_NAME);
        }

        $from = $this->fqn($node->find('#ImportFrom', 0)->getChild(0), self::I_NAME);

        foreach ($targets as $target) {
            $ctx->addLink($target, $from);
        }
    }
}
