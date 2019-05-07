<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Lexer\Token;

use Railt\Component\Lexer\Token\Common\Renderable;
use Railt\Component\Lexer\TokenInterface;

/**
 * Class BaseToken
 */
abstract class BaseToken implements TokenInterface
{
    use Renderable;

    /**
     * @var int|null
     */
    private $length;

    /**
     * @var int|null
     */
    private $bytes;

    /**
     * @return int
     */
    public function getBytes(): int
    {
        if ($this->bytes === null) {
            $this->bytes = \strlen($this->getValue());
        }

        return $this->bytes;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        if ($this->length === null) {
            $this->length = \mb_strlen($this->getValue());
        }

        return $this->length;
    }
}
