<?php

declare(strict_types=1);

namespace Railt\Http\Factory;

use Railt\Contracts\Http\Factory\RequestParserInterface;

interface CompositeRequestParserInterface extends RequestParserInterface
{
    /**
     * Returns new instance of {@see CompositeRequestParserInterface} with the
     * list of the passed parsers.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified parsers list.
     *
     * @param iterable<RequestParserInterface> $parsers
     */
    public function withParsers(iterable $parsers): self;

    /**
     * Returns new instance of {@see CompositeRequestParserInterface} with the
     * added parser to the START of the pipeline.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified prepended parser.
     */
    public function withPrependedParser(RequestParserInterface $parser): self;

    /**
     * Returns new instance of {@see CompositeRequestParserInterface} with the
     * added parser to the END of the pipeline.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified appended parser.
     */
    public function withAppendedParser(RequestParserInterface $parser): self;

    /**
     * Returns new instance of {@see CompositeRequestParserInterface} without
     * the given parser instance.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that not contains the
     *                  specified parser.
     */
    public function withoutParser(RequestParserInterface $parser): self;
}
