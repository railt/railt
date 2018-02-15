<?php
/**
 * This file is part of Lexer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Lexer\Exceptions;

use Railt\Io\Position;
use Railt\Io\Readable;

/**
 * Trait CustomErrorPosition
 */
trait CustomErrorPosition
{
    /**
     * @param Readable $readable
     * @param Position $position
     * @return void
     */
    public function inFile(Readable $readable, Position $position): void
    {
        if ($readable->isFile()) {
            $this->file = $readable->getPathname();
            $this->line = $position->getLine();
        }
    }

    /**
     * @param string $message
     * @param Readable $readable
     * @param Position $position
     * @return static
     */
    public static function fromFile(string $message, Readable $readable, Position $position)
    {
        $instance = new static($message);
        $instance->inFile($readable, $position);

        return $instance;
    }
}
