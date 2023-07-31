<?php

declare(strict_types=1);

namespace Railt\SDL\Parser;

use Phplrt\Contracts\Parser\ParserInterface as BaseParserInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Railt\SDL\Exception\ParsingException;
use Railt\SDL\Node\Node;

interface ParserInterface extends BaseParserInterface
{
    /**
     * Parses sources into an abstract source tree (AST) or list of AST nodes.
     *
     * @param string|resource|\SplFileInfo|ReadableInterface $source
     * @return iterable<Node>
     *
     * @throws ParsingException
     * @throws InvalidArgumentException
     */
    public function parse(mixed $source): iterable;
}
