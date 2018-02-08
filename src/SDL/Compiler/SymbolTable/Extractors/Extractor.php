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

/**
 * Interface Extractor
 */
interface Extractor
{
    /**@#+
     * Name extraction indexes
     */
    public const I_OFFSET = 0x00;
    public const I_NAME   = 0x01;
    /**@#-*/

    /**
     * @param Context $context
     * @param RuleInterface $node
     */
    public function extract(Context $context, RuleInterface $node): void;
}
