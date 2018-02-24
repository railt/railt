<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader\Context;

use Railt\Compiler\Generator\Grammar\Exceptions\InvalidRuleException;
use Railt\Compiler\Generator\Grammar\Lexer as T;
use Railt\Compiler\Generator\Grammar\Reader\Productions\Alternation;
use Railt\Compiler\Generator\Grammar\Reader\Productions\Concatenation;
use Railt\Compiler\Generator\Grammar\Reader\Productions\Group;
use Railt\Compiler\Generator\Grammar\Reader\Productions\Invocation;
use Railt\Compiler\Generator\Grammar\Reader\Productions\Repetition;
use Railt\Compiler\Generator\Grammar\Reader\Productions\Token as TokenRule;
use Railt\Io\Readable;

/**
 * Class Builder
 */
class Builder
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
     * @var ContextStack
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
        $this->context = new ContextStack(new Group(null, $this->name));
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

    /**
     * @param Item $item
     * @throws \OutOfBoundsException
     */
    public function collect(Item $item): void
    {
        //
        // If the current context is not a "Concatenation" sequence,
        // but the record rule is a link to a token or rule (concatenable),
        // then we must initialize the Concatenation rule.
        //
        if ($item->isConcatenable() && ! $this->context->is(Concatenation::class)) {
            $concat = new Concatenation($this->context->current());
            $this->context->current()->push($concat);
            $this->context->push($concat);
        }

        //
        // Match the current rule definition
        //
        switch (true) {
            case $item->is(T::T_KEPT):
                $this->context->current()->push(new TokenRule($item->context(0), true));
                break;

            case $item->is(T::T_SKIPPED):
                $this->context->current()->push(new TokenRule($item->context(0), false));
                break;

            case $item->is(T::T_NAMED):
                $this->context->current()->push(new Invocation($item->context(0)));
                break;

            case $item->is(T::T_OR):
                if (! $this->context->is(Alternation::class)) {
                    if ($this->context->current()->isEmpty()) {
                        $error = 'The alternation is a binary operation and is performed on two operations, ' .
                            'but current context ' . $this->context->current() . ' is empty';
                        throw InvalidRuleException::fromFile($error, $this->file, $item->position());
                    }

                    $alter = new Alternation($this->context->current());
                    $alter->push($this->context->current()->pop());
                    $this->context->current()->push($alter);
                    $this->context->push($alter);
                    break;
                }

                break;

            case $item->is(T::T_NODE):
                /**
                 * Select last group definition from stack and rename it:
                 * <code>
                 *  1) Rename global (root) group
                 *  ::TOKEN:: Call() #NewName
                 *
                 *  2) Rename the current group
                 *  (::TOKEN:: Call() #NewName)
                 * </code>
                 */
                $this->context->group()->rename((string)$item->context(0));
                break;

            case $item->is(T::T_EXACTLY_N):    // [N … N]
            case $item->is(T::T_N_TO_M):       // [N … M]
            case $item->is(T::T_ZERO_TO_M):    // [0 … M]
            case $item->is(T::T_ZERO_OR_ONE):  // [0 … 1]
            case $item->is(T::T_N_OR_MORE):    // [N … Inf]
            case $item->is(T::T_ZERO_OR_MORE): // [0 … Inf]
            case $item->is(T::T_ONE_OR_MORE):  // [1 … Inf]
                $repeat = Repetition::make($this->context->current(), $item);

                /**
                 * If repetition modifier is located immediately
                 * after the definition of the group:
                 * <code>
                 *  (::TOKEN:: Call())* // from: Group { Skip, Invoke }, Repeat
                 *                      // - Select last group and wrap it with "Repeat"
                 *                      // to: Repeat { Group { Skip, Invoke } }
                 * </code>
                 */
                if ($item->previous()->is(T::T_GROUP_CLOSE)) {
                    // Select the last closed group
                    $group = $this->context->previousGroup();

                    // Append this group to repetition
                    $repeat->push($group);

                    // Select the parent context
                    $parent = $group->parent();

                    // And now we must replace the $group to the $repeat
                    $parent->pop();
                    $parent->push($repeat);

                /**
                 * Otherwise we wrap last rule definition:
                 * <code>
                 *  ::TOKEN:: Call()* // from: Skip, Invoke, Repeat
                 *                    // - Select last token and wrap it with "Repeat"
                 *                    // to:   Skip, Repeat { Invoke }
                 * </code>
                 */
                } else {
                    // Select last used token
                    $token = $this->context->current()->pop();

                    // Append this token to repetition
                    $repeat->push($token);

                    // And now we must append the repetition to the current context.
                    $this->context->current()->push($repeat);
                }
                break;

            case $item->is(T::T_GROUP_OPEN):
                $group = new Group($this->context->current());
                $this->context->current()->push($group);
                $this->context->push($group);
                break;

            case $item->is(T::T_GROUP_CLOSE):
                $this->context->popGroup();
                break;

            default:
                throw new \OutOfBoundsException('Invalid rule definition ' . $item->name());
        }
    }

    /**
     * @return iterable
     */
    public function reduce(): iterable
    {
        $this->complete();

        return $this->context->root();
    }

    /**
     * @return void
     */
    private function complete(): void
    {
        /**
         * Just check that there is an unclosed group.
         */
        if ($this->context->groups() > 1) {
            $error    = \sprintf('The production rule "%s" has an unclosed group', $this->name);
            $position = $this->file->getPosition($this->getRuleOffset());
            throw InvalidRuleException::fromFile($error, $this->file, $position);
        }

        //
        // The rest of the status checks (then I'll think of something).
        // ...
        //
    }
}
