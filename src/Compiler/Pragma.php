<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Railt\Compiler\Grammar\Exceptions\InvalidPragmaException;
use Railt\Compiler\Pragma\Definition;
use Railt\Lexer\Configuration;

/**
 * Class Pragma
 */
class Pragma implements \IteratorAggregate
{
    /**
     * @var array|string[]|Definition[]
     */
    private $definitions = [
        // Lexer
        Pragma\LexerUnicode::class,
        Pragma\LexerMultiline::class,
        Pragma\LexerVerification::class,

        // Parser
        Pragma\ParserRootRule::class,
        Pragma\ParserLookahead::class,
    ];

    /**
     * @var array
     */
    private $values = [];

    /**
     * Pragma constructor.
     * @param array $pragmas
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidPragmaException
     */
    public function __construct(array $pragmas = [])
    {
        foreach ($pragmas as $key => $value) {
            $this->add($key, (string)$value);
        }
    }

    /**
     * @param string $name
     * @param string $value
     * @return Pragma
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidPragmaException
     */
    public function add(string $name, string $value): Pragma
    {
        $class = $this->getClassDefinition($name);

        /** @var Definition $instance */
        $instance = new $class($value);

        $this->values[$name] = $instance->getValue();

        return $this;
    }

    /**
     * @param string $name
     * @return string|Definition
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidPragmaException
     */
    private function getClassDefinition(string $name): string
    {
        foreach ($this->definitions as $class) {
            if ($class::getName() === $name) {
                return $class;
            }
        }

        throw new InvalidPragmaException(\sprintf('Could not resolve the "%s" pragma definition', $name));
    }

    /**
     * @param array $defaults
     * @return Configuration
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidPragmaException
     */
    public function lexerConfiguration(array $defaults = []): Configuration
    {
        $configs = new Configuration(\array_merge($defaults, [
            'verifyUnrecognizedTokens' => $this->get(Pragma\LexerVerification::getName()),
            'modeIsUnicode'            => $this->get(Pragma\LexerUnicode::getName()),
            'modeMultiline'            => $this->get(Pragma\LexerMultiline::getName()),
        ]));

        return $configs;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Railt\Compiler\Grammar\Exceptions\InvalidPragmaException
     */
    public function get(string $name)
    {
        if (\array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }

        return $this->getClassDefinition($name)::getDefaultValue();
    }

    /**
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        yield from $this->values;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return ['pragmas' => $this->values];
    }
}
