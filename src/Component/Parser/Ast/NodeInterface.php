<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Parser\Ast;

use Railt\Component\Parser\Dumper\NodeDumperInterface;

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
     * @param string $name
     * @return bool
     */
    public function is(string $name): bool;

    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @return iterable|string[]|\Generator
     */
    public function getValues(): iterable;

    /**
     * @param int $group
     * @return string|null
     */
    public function getValue(int $group = 0): ?string;

    /**
     * @param NodeDumperInterface|string $dumper
     * @return string
     */
    public function dump(string $dumper): string;
}
