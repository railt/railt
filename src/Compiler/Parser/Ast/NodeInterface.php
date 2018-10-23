<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser\Ast;

/**
 * Interface NodeInterface
 */
interface NodeInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @param string $name
     * @return bool
     */
    public function is(string $name): bool;

    /**
     * @return mixed
     */
    public function getValue();
}
