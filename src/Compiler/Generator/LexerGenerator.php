<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator;

use Railt\Compiler\Lexer;
use Railt\Compiler\LexerInterface;

/**
 * Class LexerGenerator
 */
class LexerGenerator extends BaseCodeGenerator
{
    /**
     * @var string
     */
    protected $template = 'lexer.php.twig';

    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * LexerGenerator constructor.
     * @param Lexer $lexer
     */
    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * @return \Generator
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     */
    protected function getContext(): \Generator
    {
        yield from parent::getContext();

        $c = $this->lexer->getCompiler();

        yield 'pattern' => $c->compile();
        yield 'tokens' => $c->getTokens();
    }
}
