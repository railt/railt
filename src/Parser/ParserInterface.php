<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;

/**
 * Interface ParserInterface
 */
interface ParserInterface
{
    /**
     * @param Readable $input
     * @return RuleInterface
     */
    public function parse(Readable $input): RuleInterface;

    /**
     * @param string $rule
     * @param \Closure $then
     * @return ParserInterface|$this
     */
    public function extend(string $rule, \Closure $then): self;
}
