<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Trace;

use Railt\Lexer\TokenInterface;

/**
 * Class Token
 * @internal the class is part of the internal logic
 */
class Token extends TraceItem
{
    /**
     * @var TokenInterface
     */
    private $token;

    /**
     * @var bool
     */
    private $kept;

    /**
     * Token constructor.
     * @param TokenInterface $token
     * @param bool $kept
     */
    public function __construct(TokenInterface $token, bool $kept)
    {
        $this->kept = $kept;
        $this->token = $token;
        $this->at($token->getOffset());
    }

    /**
     * @return TokenInterface
     */
    public function getToken(): TokenInterface
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function isKept(): bool
    {
        return $this->kept;
    }
}
