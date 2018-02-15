<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Pragma;

use Railt\Compiler\Grammar\Exceptions\InvalidPragmaException;
use Railt\Compiler\Pragma;

/**
 * Class BaseDefinition
 */
abstract class BaseDefinition implements Definition
{
    /**
     * @var string
     */
    private $value;

    /**
     * BaseDefinition constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidPragmaException
     */
    public function toBoolean(): bool
    {
        switch (\mb_strtolower($this->value)) {
            case 'true':
            case '1':
                return true;
            case 'false':
            case '0':
                return false;
        }

        $error = 'The value of "%s" pragma must be a boolean, but %s given';
        throw new InvalidPragmaException(\sprintf($error, $this->getName(), $this->value));
    }

    /**
     * @return int
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidPragmaException
     */
    public function toInt(): int
    {
        if ($this->value === (string)(int)$this->value) {
            return (int)$this->value;
        }

        $error = 'The value of "%s" pragma must be an integer, but %s given';
        throw new InvalidPragmaException(\sprintf($error, $this->getName(), $this->value));
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * @param string $base
     * @return string
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidPragmaException
     */
    public function toClassOf(string $base): string
    {
        $class = $this->toClass();

        if (\is_subclass_of($base, $class)) {
            return $class;
        }

        $error = 'The class %s defined by pragma "%s" must be an instance of %s';
        throw new InvalidPragmaException(\sprintf($error, $this->value, $this->getName(), $base));
    }

    /**
     * @return string
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidPragmaException
     */
    public function toClass(): string
    {
        if (\class_exists($this->value)) {
            return $this->value;
        }

        $error = 'The value of "%s" pragma must be a valid class name, but %s given';
        throw new InvalidPragmaException(\sprintf($error, $this->getName(), $this->value));
    }

}
