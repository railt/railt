<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests\Parser;

use Phplrt\Contracts\Parser\Exception\ParserRuntimeExceptionInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Railt\SDL\Frontend\Ast\Node;
use Railt\SDL\Frontend\Parser;
use Railt\SDL\Tests\TestCase;

/**
 * Class ParserTestCase
 */
abstract class ParserTestCase extends TestCase
{
    /**
     * @param string|resource|ReadableInterface|mixed $source
     * @return Node[]
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function parse($source): array
    {
        /** @var array $result */
        $result = (new Parser())->parse($source);

        return $result;
    }

    /**
     * @param string|resource|ReadableInterface|mixed $source
     * @return Node
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    protected function parseFirst($source): Node
    {
        foreach ($this->parse($source) as $node) {
            return $node;
        }

        throw new \LogicException('Parse method should contain an AST node');
    }
}
