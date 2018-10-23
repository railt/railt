<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator;

use Railt\Compiler\Grammar\ParsingResult;
use Railt\Compiler\Lexer\Common\PCRECompiler;

/**
 * Class ParserGenerator
 */
class ParserGenerator extends BaseCodeGenerator
{
    /**
     * @var string
     */
    protected $template = 'parser/llk.php.twig';

    /**
     * @var ParsingResult
     */
    private $result;

    /**
     * ParserGenerator constructor.
     * @param ParsingResult $result
     */
    public function __construct(ParsingResult $result)
    {
        $this->result = $result;
    }

    /**
     * @return \Generator
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     */
    protected function getContext(): \Generator
    {
        $lexer = $this->result->getLexer();

        yield from parent::getContext();

        $pcre = new PCRECompiler($lexer->getTokens());

        yield 'pattern' => $pcre->compile();
        yield 'tokens' => $lexer->getTokens();
        yield 'skip' => $lexer->getIgnoredTokens();
        yield 'lexer' => $this->result->getLexer();
        yield 'rules' => $this->result->getBuilders();
    }
}
