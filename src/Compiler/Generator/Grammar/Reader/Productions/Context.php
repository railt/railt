<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader\Productions;

use Railt\Compiler\Generator\Grammar\Exceptions\InvalidRuleException;
use Railt\Compiler\Generator\Grammar\Lexer as T;
use Railt\Compiler\Runtime\Ast\Leaf;
use Railt\Compiler\Runtime\Ast\LeafInterface;
use Railt\Compiler\Runtime\Ast\RuleInterface;
use Railt\Io\Readable;

/**
 * Class Context
 */
class Context
{
    /**
     * @var Readable
     */
    private $file;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var Definition|LeafInterface[]
     */
    private $ast;

    /**
     * @var Definition|LeafInterface[]
     */
    private $context;

    /**
     * Context constructor.
     * @param Readable $file
     * @param string $name
     * @param int $offset
     */
    public function __construct(Readable $file, string $name, int $offset)
    {
        $this->file    = $file;
        $this->name    = $name;
        $this->offset  = $offset;
        $this->context = $this->ast = new Definition($this->name);
    }

    /**
     * @return string
     */
    public function getRuleName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getRuleOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return Readable
     */
    public function getFile(): Readable
    {
        return $this->file;
    }

    public function collect(InputRule $rule)
    {
        switch (true) {
            case $rule->is(T::T_KEPT):
            case $rule->is(T::T_SKIPPED):
                if (! $this->context->is(Concatenation::class)) {
                    $concat = new Concatenation();
                    $this->context->append($concat);
                    $this->context = $concat;
                }

                $this->context->append(new Token($rule));
                break;

            case $rule->is(T::T_OR):
                if (! $this->context->is(Alternation::class)) {
                    if ($this->context->count() === 0) {
                        $error = 'The alternation is a binary operation and is performed on two operations, ' .
                            'but current context ' . $this->context . ' is empty';
                        throw InvalidRuleException::fromFile($error, $this->file, $rule->position());
                    }

                    $alter = new Alternation();
                    $alter->append($this->context->pop());
                    $this->context->append($alter);
                    $this->context = $alter;
                }
                break;

            //default:
                //$this->context->append(new Leaf($rule->name(), $rule->value()));
        }
    }

    /**
     * @return iterable
     */
    public function reduce(): iterable
    {
        $this->complete();

        return $this->ast;
    }

    /**
     * @return void
     */
    private function complete(): void
    {

    }
}
