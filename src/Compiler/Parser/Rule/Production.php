<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser\Rule;

/**
 * Interface Production
 */
interface Production extends Symbol
{
    /**
     * References to a subsequent rule (symbol).
     *
     * @return array|int[]|string[]
     */
    public function then(): array;

    /**
     * The name of the rule that should be inside the abstract syntax tree.
     *
     * The name is only available for those rules that must be contained
     * within the resulting AST otherwise this method should return NULL.
     *
     * @return null|string
     */
    public function getName(): ?string;
}
