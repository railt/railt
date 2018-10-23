<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Iterator;

/**
 * An iterator which returns a list of regex named groups
 */
class RegexNamedGroupsIterator extends RegexIterator
{
    /**
     * @return \Traversable|\Generator|array[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getIterator(): \Traversable
    {
        foreach (parent::getIterator() as $result) {
            $context = [];

            foreach (\array_reverse($result) as $index => $body) {
                if (! \is_string($index)) {
                    $context[] = $body;
                    continue;
                }

                if ($body !== '') {
                    yield $index => \array_reverse($context);
                    continue 2;
                }
            }
        }
    }
}
