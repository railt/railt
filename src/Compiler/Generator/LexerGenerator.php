<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator;

use Railt\Compiler\Generator\Renderer\Renderer;
use Railt\Compiler\Generator\Renderer\TwigRenderer;
use Railt\Compiler\Lexer\Runtime;
use Railt\Compiler\LexerInterface;

/**
 * Class LexerGenerator
 */
class LexerGenerator extends BaseCodeGenerator
{
    /**
     * @var LexerInterface
     */
    private $lexer;

    /**
     * LexerGenerator constructor.
     * @param LexerInterface|Runtime $lexer
     */
    public function __construct(Runtime $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        return 'lexer.php.twig';
    }

    /**
     * @return Renderer
     */
    protected function getRenderer(): Renderer
    {
        return new TwigRenderer();
    }

    /**
     * @return \Generator
     * @throws \Zend\Code\Generator\Exception\RuntimeException
     */
    protected function getContext(): \Generator
    {
        yield from parent::getContext();

        yield 'pattern' => $this->lexer->pattern();
        yield 'identifiers' => $this->lexer->identifiers();
        yield 'channels' => $this->lexer->channels();
    }
}
