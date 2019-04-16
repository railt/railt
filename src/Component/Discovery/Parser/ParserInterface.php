<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Discovery\Parser;

/**
 * Interface ParserInterface
 */
interface ParserInterface
{
    /**
     * @param string $variable
     * @param string|\Closure $value
     * @return ParserInterface
     */
    public function define(string $variable, $value): self;

    /**
     * @param iterable $variables
     * @return ParserInterface
     */
    public function defineAll(iterable $variables): self;

    /**
     * @param mixed $value
     * @return mixed
     */
    public function bypass($value);
}
