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
use Railt\SDL\Compiler\SymbolTable\Record;

/**
 * Interface Extractor
 */
interface Extractor
{
    /**
     * @param RuleInterface $rule
     * @return bool
     */
    public function match(RuleInterface $rule): bool;

    /**
     * @param Readable $input
     * @param RuleInterface $rule
     * @return Record
     */
    public function extract(Readable $input, RuleInterface $rule): Record;
}
