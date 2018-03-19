<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer;

/**
 * Class Definition
 */
class Definition
{
    /**@#+
     * A default token channels.
     */
    public const CHANNEL_DEFAULT = Token::CHANNEL_DEFAULT;
    public const CHANNEL_SKIP = Token::CHANNEL_SKIP;
    public const CHANNEL_SYSTEM = Token::CHANNEL_SYSTEM;
    /**@#-*/

    /**
     * @var int
     */
    private static $lastId = 0xff;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $channel = self::CHANNEL_DEFAULT;

    /**
     * @var string|int
     */
    private $name;

    /**
     * Token constructor.
     * @param string|int $name
     * @param string $value
     * @param int|null $id
     */
    public function __construct($name, string $value, int $id = null)
    {
        $this->id = $id ?? self::$lastId++;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string|int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $channel
     * @return Definition
     */
    public function in(string $channel): Definition
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->channel === self::CHANNEL_SKIP;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
