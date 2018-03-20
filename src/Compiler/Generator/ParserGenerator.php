<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator;

use Railt\Compiler\Parser;

/**
 * Class ParserGenerator
 */
class ParserGenerator extends BaseCodeGenerator
{
    /**
     * @var string
     */
    protected $template = 'parser.php.twig';

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    private $lexerClass;

    /**
     * ParserGenerator constructor.
     * @param Parser $parser
     * @param string $lexer
     */
    public function __construct(Parser $parser, string $lexer)
    {
        $this->parser     = $parser;
        $this->lexerClass = $lexer;
    }

    /**
     * @return \Generator
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     */
    protected function getContext(): \Generator
    {
        yield from parent::getContext();

        yield 'lexer' => $this->lexerClass;
        yield 'rules' => $this->parser->getRules();
    }
}
