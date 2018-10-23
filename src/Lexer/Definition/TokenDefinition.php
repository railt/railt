<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer\Definition;

/**
 * Class TokenDefinition
 */
class TokenDefinition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $pcre;

    /**
     * @var bool
     */
    private $keep;

    /**
     * TokenDefinition constructor.
     * @param string $name
     * @param string $pcre
     * @param bool $keep
     */
    public function __construct(string $name, string $pcre, bool $keep = true)
    {
        $this->name = $name;
        $this->pcre = $pcre;
        $this->keep = $keep;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPcre(): string
    {
        return $this->pcre;
    }

    /**
     * @return bool
     */
    public function isKeep(): bool
    {
        return $this->keep;
    }
}
