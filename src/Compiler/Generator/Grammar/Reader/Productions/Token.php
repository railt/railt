<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader\Productions;

use Railt\Compiler\Generator\Grammar\Lexer;
use Railt\Compiler\Runtime\Ast\Leaf;

/**
 * Class Token
 */
class Token extends Leaf
{
    /**
     * @var bool
     */
    private $keep;

    /**
     * Token constructor.
     * @param InputRule $input
     */
    public function __construct(InputRule $input)
    {
        $this->keep = $input->is(Lexer::T_KEPT);
        parent::__construct($input->name(), (string)$input->context(0), $input->offset());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return \class_basename($this) . ': "' .
            $this->getValue() . '"' . ($this->keep ? ' -> ' : '');
    }
}
