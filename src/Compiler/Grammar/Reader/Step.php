<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader;

use Railt\Compiler\TokenInterface;
use Railt\Io\Readable;

/**
 * Interface Step
 */
interface Step
{
    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function match(TokenInterface $token): bool;

    /**
     * @param Readable $file
     * @param TokenInterface $token
     */
    public function parse(Readable $file, TokenInterface $token): void;
}
