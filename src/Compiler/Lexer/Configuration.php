<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

use Railt\Compiler\Lexer\Exceptions\LexerException;
use Railt\Compiler\Lexer\Exceptions\UnrecognizedTokenException;

/**
 * Class Configuration
 * @property bool $verifyUnrecognizedTokens
 * @property string|LexerException $unrecognizedTokenException
 * @property bool $addEndOfFileToken
 * @property bool $modeIsUnicode
 * @property bool $modeMultiline
 * @property bool $modeDotAll
 * @property bool $pcreJit
 */
final class Configuration
{
    /**
     * @var bool
     */
    private $verifyUnrecognizedTokens = true;

    /**
     * @var string
     */
    private $unrecognizedTokenException = UnrecognizedTokenException::class;

    /**
     * @var bool
     */
    private $modeIsUnicode = true;

    /**
     * @var bool
     */
    private $modeDotAll = false;

    /**
     * @var bool
     */
    private $modeMultiline = true;

    /**
     * Configuration constructor.
     * @param iterable $options
     */
    public function __construct(iterable $options = [])
    {
        foreach ($options as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * @param string $option
     * @param $value
     * @return void
     */
    public function set(string $option, $value): void
    {
        $this->$option = $value;
    }

    /**
     * @param iterable $options
     * @return static|Configuration
     */
    public static function new(iterable $options = []): self
    {
        return new static($options);
    }

    /**
     * @param string $name
     * @return null
     */
    public function __get(string $name)
    {
        return \property_exists($this, $name) ? $this->$name : null;
    }

    /**
     * @param string $name
     * @param $value
     * @throws \LogicException
     */
    public function __set(string $name, $value): void
    {
        if ($this->__isset($name)) {
            throw new \LogicException('Could not update already configured value "' . $name . '"');
        }

        throw new \LogicException('The configuration option "' . $name . '" does not exists');
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return \property_exists($this, $name);
    }
}
