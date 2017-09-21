<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard\Base;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\DirectiveType;
use Railt\Reflection\Standard\Common\HasDeprecation;
use Railt\Reflection\Standard\Common\HasName;
use Railt\Reflection\Standard\StandardType;

/**
 * Class BaseDirective
 */
abstract class BaseDirective implements DirectiveType, StandardType
{
    use HasName;
    use HasDeprecation;

    /**
     * @var array
     */
    protected $locations = [];

    /**
     * @var array|ArgumentType[]
     */
    protected $arguments = [];

    /**
     * @var Document
     */
    private $document;

    /**
     * BaseDirective constructor.
     * @param Document $document
     * @param string $name
     */
    public function __construct(Document $document, string $name)
    {
        $this->name = $name;
        $this->document = $document;
    }

    /**
     * @return iterable|string[]
     */
    public function getLocations(): iterable
    {
        return \array_values($this->locations);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasLocation(string $name): bool
    {
        return \array_key_exists($name, $this->locations);
    }

    /**
     * @param string $name
     * @return null|string
     */
    public function getLocation(string $name): ?string
    {
        return $this->locations[$name] ?? null;
    }

    /**
     * @return iterable|ArgumentType[]
     */
    public function getArguments(): iterable
    {
        return \array_values($this->arguments);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasArgument(string $name): bool
    {
        return \array_key_exists($name, $this->arguments);
    }

    /**
     * @param string $name
     * @return null|ArgumentType
     */
    public function getArgument(string $name): ?ArgumentType
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfArguments(): int
    {
        return \count($this->arguments);
    }

    /**
     * @return int
     */
    public function getNumberOfRequiredArguments(): int
    {
        $filtered = \array_filter($this->arguments, function (ArgumentType $argument): bool {
            return !$argument->hasDefaultValue();
        });

        return \count($filtered);
    }

    /**
     * @return int
     */
    public function getNumberOfOptionalArguments(): int
    {
        $filtered = \array_filter($this->arguments, function (ArgumentType $argument): bool {
            return $argument->hasDefaultValue();
        });

        return \count($filtered);
    }

    /**
     * @param ArgumentType $argument
     * @return BaseDirective
     */
    protected function addArgument(ArgumentType $argument): BaseDirective
    {
        $this->arguments[$argument->getName()] = $argument;
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
        return 'Directive';
    }
}
