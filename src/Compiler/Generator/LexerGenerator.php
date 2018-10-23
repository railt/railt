<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator;

use Railt\Compiler\Lexer\Common\PCRECompiler;
use Railt\Compiler\Lexer\Stateless;
use Railt\Compiler\LexerInterface;

/**
 * Class LexerGenerator
 */
class LexerGenerator extends BaseCodeGenerator
{
    private const DEFAULT_TEMPLATE = 'lexer/native.php.twig';

    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * LexerGenerator constructor.
     * @param Stateless $lexer
     * @param string $template
     */
    public function __construct(Stateless $lexer, string $template = self::DEFAULT_TEMPLATE)
    {
        $this->lexer    = $lexer;
        $this->template = $template;
    }

    /**
     * @return \Generator
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     */
    protected function getContext(): \Generator
    {
        $pcre = new PCRECompiler($this->lexer->getTokens());

        yield from parent::getContext();

        yield 'pattern' => $pcre->compile();
        yield 'tokens' => $this->lexer->getTokens();
        yield 'skip' => $this->lexer->getIgnoredTokens();
    }
}
