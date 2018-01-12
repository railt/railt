<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Ast;

use Railt\Compiler\Lexer\Token;

/**
 * Class Leaf
 */
class Leaf extends Node implements LeafInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var string|null
     */
    private $namespace;

    /**
     * Leaf constructor.
     * @param string $name
     * @param string $value
     * @param int $offset
     * @param string $namespace
     */
    public function __construct(string $name, string $value, int $offset = 0, string $namespace = null)
    {
        parent::__construct($name);

        $this->value     = $value;
        $this->offset    = $offset;
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace ?? Token::T_DEFAULT_NAMESPACE;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }
}
