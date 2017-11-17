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
use Hoa\Consistency;
use Hoa\Visitor;

/**
 * Class \Hoa\Compiler\Llk\Sampler.
 *
 * Sampler parent.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
abstract class Sampler
{
    /**
     * Compiler.
     *
     * @var \Hoa\Compiler\Llk\Parser
     */
    protected $_compiler         = null;

    /**
     * Tokens.
     *
     * @var array
     */
    protected $_tokens           = null;

    /**
     * All rules (from the compiler).
     *
     * @var array
     */
    protected $_rules            = null;

    /**
     * Token sampler.
     *
     * @var \Hoa\Visitor\Visit
     */
    protected $_tokenSampler     = null;

    /**
     * Root rule name.
     *
     * @var string
     */
    protected $_rootRuleName     = null;

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
     * @param   \Hoa\Compiler\Llk\Parser  $compiler        Compiler/parser.
     * @param   \Hoa\Visitor\Visit        $tokenSampler    Token sampler.
     */
    public function __construct(
        Compiler\Llk\Parser $compiler,
        Visitor\Visit       $tokenSampler
    ) {
        $this->_compiler     = $compiler;
        $this->_tokens       = $compiler->getTokens();
        $this->_rules        = $compiler->getRules();
        $this->_tokenSampler = $tokenSampler;
        $this->_rootRuleName = $compiler->getRootRule();

        return;
    }

    /**
     * Get compiler.
     *
     * @return  \Hoa\Compiler\Llk\Parser
     */
    public function getCompiler()
    {
        return $this->_compiler;
    }

    /**
     * Get the AST of the current namespace skip token.
     *
     * @return  \Hoa\Compiler\Llk\TreeNode
     */
    protected function getSkipTokenAST()
    {
        if (!isset($this->_skipTokenAST[$this->_currentNamespace])) {
            $token = new Compiler\Llk\Rule\Token(
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
     * @param   \Hoa\Compiler\Llk\Rule\Token  $token    Token.
     * @return  string
     */
    protected function completeToken(Compiler\Llk\Rule\Token $token)
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
                if (false === strpos($_name, ':')) {
                    continue;
                }

                list($_name, $toNamespace) = explode(':', $_name, 2);

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
     * @param   string  $namespace    Token namespace.
     * @return  string
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
     * @param   \Hoa\Compiler\Llk\Rule\Token  $token    Token.
     * @return  string
     */
    protected function generateToken(Compiler\Llk\Rule\Token $token)
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

/**
 * Flex entity.
 */
Consistency::flexEntity('Hoa\Compiler\Llk\Sampler\Sampler');
