<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Lexer\Tokens;

/**
 * Class Channel
 */
final class Channel
{
    /**
     * The default output channel name.
     */
    public const DEFAULT = 'default';

    /**
     * The default output channel name with list of ignored tokens.
     */
    public const SKIPPED = 'skip';

    /**
     * The default output channel name with list of system tokens (e.g. EOF).
     */
    public const SYSTEM = 'system';
}
