<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Builder;

use Railt\Compiler\Parser\Rule\Token;

/**
 * Class TokenBuilder
 */
class TokenBuilder extends Token implements Buildable
{
    use Movable;
    use Instantiable;

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [$this->id, $this->name, $this->kept];
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return Token::class;
    }
}
