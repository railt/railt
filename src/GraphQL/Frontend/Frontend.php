<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\Frontend;

use Railt\GraphQL\Exception\SyntaxException;
use Railt\Io\Exception\ExternalFileException;
use Railt\Io\Readable;
use Railt\Parser\Ast\RuleInterface;
use Railt\Parser\ParserInterface;

/**
 * Class Frontend
 */
final class Frontend
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * Frontend constructor.
     * @throws \InvalidArgumentException
     * @throws \Railt\Lexer\Exception\BadLexemeException
     */
    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     * @param Readable $schema
     * @return RuleInterface
     * @throws SyntaxException
     * @throws \LogicException
     */
    public function parse(Readable $schema): RuleInterface
    {
        try {
            return $this->parser->parse($schema);
        } catch (ExternalFileException $e) {
            $exception = new SyntaxException($e->getMessage());
            $exception->throwsIn($schema, $e->getLine(), $e->getColumn());

            throw $exception;
        }
    }
}
