<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Hoa\Compiler\Llk\Sampler;

use Hoa\Compiler;
use Hoa\Compiler\Exception\Exception;
use Hoa\Compiler\Llk\Parser;
use Hoa\Compiler\Llk\Rule\Token;
use Hoa\Compiler\Llk\TreeNode;
use Hoa\Visitor;
use Hoa\Visitor\Visit;

/**
 * Class \Hoa\Compiler\Llk\Sampler.
 *
 * Sampler parent.
 *
 * @copyright Copyright © 2007-2017 Hoa community
 * @license New BSD License
 */
abstract class Sampler
{
    /**
     * Compiler.
     *
     * @var Parser
     */
    protected $_compiler;

    /**
     * Tokens.
     *
     * @var array
     */
    protected $_tokens;

    /**
     * All rules (from the compiler).
     *
     * @var array
     */
    protected $_rules;

    /**
     * Token sampler.
     *
     * @var Visit
     */
    protected $_tokenSampler;

    /**
     * Root rule name.
     *
     * @var string
     */
    protected $_rootRuleName;

    /**
     * Current token namespace.
     *
     * @var string
     */
    protected $_currentNamespace = 'default';

    /**
     * Skip tokens AST.
     *
     * @var array
     */
    protected $_skipTokenAST     = [];

    /**
     * Construct a generator.
     *
     * @param Parser $compiler Compiler/parser.
     * @param Visit $tokenSampler Token sampler.
     */
    public function __construct(
        Parser $compiler,
        Visit       $tokenSampler
    ) {
        $this->_compiler     = $compiler;
        $this->_tokens       = $compiler->getTokens();
        $this->_rules        = $compiler->getRules();
        $this->_tokenSampler = $tokenSampler;
        $this->_rootRuleName = $compiler->getRootRule();
    }

    /**
     * Get compiler.
     *
     * @return Parser
     */
    public function getCompiler(): Parser
    {
        return $this->_compiler;
    }

    /**
     * Get the AST of the current namespace skip token.
     *
     * @return TreeNode
     * @throws \InvalidArgumentException
     * @throws \Hoa\Compiler\Exception\UnexpectedToken
     * @throws Exception
     */
    protected function getSkipTokenAST(): TreeNode
    {
        if (! isset($this->_skipTokenAST[$this->_currentNamespace])) {
            $token = new Token(
                -1,
                'skip',
                null,
                -1
            );

            $token->setRepresentation(
                $this->_tokens[$this->_currentNamespace]['skip']
            );

            $this->_skipTokenAST[$this->_currentNamespace] = $token->getAST();
        }

        return $this->_skipTokenAST[$this->_currentNamespace];
    }

    /**
     * Complete a token (namespace and representation).
     * It returns the next namespace.
     *
     * @param Token $token Token.
     * @return string
     */
    protected function completeToken(Token $token)
    {
        if (null !== $token->getRepresentation()) {
            return $this->_currentNamespace;
        }

        $name = $token->getTokenName();
        $token->setNamespace($this->_currentNamespace);
        $toNamespace = $this->_currentNamespace;

        if (isset($this->_tokens[$this->_currentNamespace][$name])) {
            $token->setRepresentation(
                $this->_tokens[$this->_currentNamespace][$name]
            );
        } else {
            foreach ($this->_tokens[$this->_currentNamespace] as $_name => $regex) {
                if (false === \strpos($_name, ':')) {
                    continue;
                }

                [$_name, $toNamespace] = \explode(':', $_name, 2);

                if ($_name === $name) {
                    break;
                }
            }

            $token->setRepresentation($regex);
        }

        return $toNamespace;
    }

    /**
     * Set current token namespace.
     *
     * @param string $namespace Token namespace.
     * @return string
     */
    protected function setCurrentNamespace($namespace)
    {
        $old                     = $this->_currentNamespace;
        $this->_currentNamespace = $namespace;

        return $old;
    }

    /**
     * Generate a token value.
     * Complete and set next token namespace.
     *
     * @param Token $token Token.
     * @return string
     * @throws Exception
     */
    protected function generateToken(Token $token)
    {
        $toNamespace = $this->completeToken($token);
        $this->setCurrentNamespace($toNamespace);

        $out = $this->_tokenSampler->visit($token->getAST());

        if (isset($this->_tokens[$this->_currentNamespace]['skip'])) {
            $out .= $this->_tokenSampler->visit($this->getSkipTokenAST());
        }

        return $out;
    }
}
