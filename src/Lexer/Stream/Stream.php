<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer\Stream;

/**
 * Interface Stream
 */
interface Stream extends \IteratorAggregate
{
    /**
     * @param string[] ...$names
     * @return Stream
     */
    public function channel(string ...$names): Stream;

    /**
     * @param string[] ...$names
     * @return Stream
     */
    public function exceptChannel(string ...$names): Stream;

    /**
     * @param string[] ...$names
     * @return Stream
     */
    public function token(string ...$names): Stream;

    /**
     * @param string[] ...$names
     * @return Stream
     */
    public function exceptToken(string ...$names): Stream;

    /**
     * @param \Closure $filter
     * @return Stream
     */
    public function filter(\Closure $filter): Stream;

    /**
     * @return \Traversable
     */
    public function get(): \Traversable;
}
