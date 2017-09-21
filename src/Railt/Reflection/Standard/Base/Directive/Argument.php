<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard\Base\Directive;

use Railt\Reflection\Contracts\Behavior\Inputable;
use Railt\Reflection\Contracts\Behavior\Nameable;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\Directive\DirectiveInvocation;
use Railt\Reflection\Contracts\Types\DirectiveType;
use Railt\Reflection\Standard\Base\BaseDocument;
use Railt\Reflection\Standard\Common\HasDeprecation;
use Railt\Reflection\Standard\Common\HasName;

/**
 * Class Argument
 */
class Argument implements ArgumentType
{
    use HasName;
    use HasDeprecation;

    /**
     * @var array
     */
    protected $directives = [];

    /**
     * @var Document
     */
    private $document;

    /**
     * @var bool
     */
    protected $hasDefaultValue = false;

    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @var DirectiveType
     */
    private $parent;

    /**
     * Argument constructor.
     * @param Document|BaseDocument $document
     * @param string $name
     * @param DirectiveType $parent
     */
    public function __construct(Document $document, string $name, DirectiveType $parent)
    {
        $this->name = $name;
        $this->document = $document;
        $this->parent = $parent;
    }

    /**
     * @return iterable
     */
    public function getDirectives(): iterable
    {
        return \array_values($this->directives);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasDirective(string $name): bool
    {
        return \array_key_exists($name, $this->directives);
    }

    /**
     * @param string $name
     * @return null|DirectiveInvocation
     */
    public function getDirective(string $name): ?DirectiveInvocation
    {
        return $this->directives[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfDirectives(): int
    {
        return \count($this->directives);
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        if ($this->hasDefaultValue) {
            return $this->defaultValue;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    /**
     * TODO
     * @return Inputable
     * @throws \LogicException
     */
    public function getType(): Inputable
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * TODO
     * @return bool
     * @throws \LogicException
     */
    public function isList(): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * TODO
     * @return bool
     * @throws \LogicException
     */
    public function isNonNull(): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * TODO
     * @return bool
     * @throws \LogicException
     */
    public function isNonNullList(): bool
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }

    /**
     * @return Nameable|DirectiveType
     */
    public function getParent(): Nameable
    {
        return $this;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Argument';
    }
}
